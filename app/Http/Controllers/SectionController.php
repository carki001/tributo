<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Image;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use App\Models\Attachment;

class SectionController extends Controller
{
    private $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(array('driver' => 'imagick'));
    }

    public function index()
    {
        $sections = Section::orderBy('position')->get();
        $maxPosition = Section::max('position');

        foreach ($sections as $sec) {
            $attachment = Attachment::where('record_id', $sec->id)->where('model_name', 'home-section-image')->first();
            $sec->file = $attachment ? $attachment : null;

            $truncated_length = 60;
            if (strlen($sec->description) > $truncated_length) {
                $sec->short_description = substr($sec->description, 0, $truncated_length) . "...";
            } else {
                $sec->short_description = $sec->description;
            }
        }

        return ['sections' => $sections, 'maxPosition' => $maxPosition];
    }


    public function store(Request $request)
    {
        $duplicatedPositionSection = Section::where('position', intval($request->position))->first();
        if ($duplicatedPositionSection) {
            $duplicatedPositionSection->position = intval($request->position) + 1;
            $duplicatedPositionSection->save();
        }

        $section = new Section();
        $section->title = $request->title;
        $section->alias = $request->alias;
        $section->description = $request->description;
        $section->position = $request->position;
        $section->save();

        $nextSections = Section::where('position', '>',  intval($section->position))->orderBy('position')->get();

        foreach ($nextSections as $index => $nextSection) {
            if ($index > 0) {
                $nextSection->position = $nextSections[$index - 1]->position + 1;
            } elseif (!$duplicatedPositionSection) {
                $nextSection->position = $section->position + 1;
            }
            $nextSection->save();
        }

        $file = $request->file;
        if ($file == "none") {
            $attachmentToDelete = Attachment::where('record_id', $section->id)->where('model_name', 'home-section-image')->first();
            if ($attachmentToDelete) {
                Storage::disk('public_uploads')->delete($attachmentToDelete->path . '/' . $attachmentToDelete->name);
                $attachmentToDelete->delete();
            }
        } elseif ($file == "keep_same_file") {
            $attachment = Attachment::where('record_id', $section->id)->where('model_name', 'home-section-image')->first();
        } else {
            $fileName = $file->getClientOriginalName();
            $fileName = preg_replace("([^\w\d\-_~,;.\[\]\(\)])", "-", $fileName);
            $fileName = ltrim($fileName, '.-');
            $file_size = $request->file('file')->getSize();

            $attachmentToDelete = Attachment::where('record_id', $section->id)->where('model_name', 'home-section-image')->first();
            if ($attachmentToDelete) {
                Storage::disk('public_uploads')->delete($attachmentToDelete->path . '/' . $attachmentToDelete->name);
                $attachmentToDelete->delete();
            }

            $attachment = new Attachment();
            $attachment->name = $fileName;
            $attachment->path = $request->model_name . '/' . $section->id;
            $attachment->file_extension = $request->file('file')->extension();
            $attachment->file_type = $request->file('file')->getMimeType();
            $attachment->file_size = $request->file('file')->getSize();
            $attachment->record_id = $section->id;
            $attachment->model_name = $request->model_name;
            $attachment->save();

            Storage::disk('public_uploads')->putFileAs($request->model_name . '/' . $section->id, $file, $fileName, 'public');
        }

        return response()->json($section, 200);
    }

    public function update(Request $request)
    {

        $duplicatedPositionSection = Section::where('position', intval($request->position))->first();


        $section = Section::findOrFail($request->id);
        if ($section != $duplicatedPositionSection) {
            $duplicatedPositionSection->position = $section->position;
            $duplicatedPositionSection->save();
        }

        $section->title = $request->title;
        $section->alias = $request->alias;
        $section->description = $request->description;
        $section->position = $request->position;
        $section->save();

        $file = $request->file;
        if ($file == "none") {
            $attachmentToDelete = Attachment::where('record_id', $request->id)->where('model_name', 'home-section-image')->first();
            if ($attachmentToDelete) {
                Storage::disk('public_uploads')->delete($attachmentToDelete->path . '/' . $attachmentToDelete->name);
                $attachmentToDelete->delete();
            }
        } elseif ($file == "keep_same_file") {
            $attachment = Attachment::where('record_id', $request->id)->where('model_name', 'home-section-image')->first();
        } else {
            $fileName = $file->getClientOriginalName();
            $fileName = preg_replace("([^\w\d\-_~,;.\[\]\(\)])", "-", $fileName);
            $fileName = ltrim($fileName, '.-');
            $file_size = $request->file('file')->getSize();

            $attachmentToDelete = Attachment::where('record_id', $request->id)->where('model_name', 'home-section-image')->first();
            if ($attachmentToDelete) {
                Storage::disk('public_uploads')->delete($attachmentToDelete->path . '/' . $attachmentToDelete->name);
                $attachmentToDelete->delete();
            }

            $attachment = new Attachment();
            $attachment->name = $fileName;
            $attachment->path = $request->model_name . '/' . $request->id;
            $attachment->file_extension = $request->file('file')->extension();
            $attachment->file_type = $request->file('file')->getMimeType();
            $attachment->file_size = $request->file('file')->getSize();
            $attachment->record_id = $request->id;
            $attachment->model_name = $request->model_name;
            $attachment->save();

            Storage::disk('public_uploads')->putFileAs($request->model_name . '/' . $request->id, $file, $fileName, 'public');
        }

        if (isset($attachment)) {
            $section->file = $attachment;
        }

        return response()->json($section, 200);
    }

    public function delete($id, Request $request)
    {
        $section = Section::findOrFail($id);

        $attachmentToDelete = Attachment::where('record_id', $id)->where('model_name', 'home-section-image')->first();
        if ($attachmentToDelete) {
            Storage::disk('public_uploads')->delete($attachmentToDelete->path . '/' . $attachmentToDelete->name);
            $attachmentToDelete->delete();
        }

        $section->delete();

        $nextSections = Section::where('position', '>',  $section->position)->orderBy('position')->get();

        foreach ($nextSections as $nextSection) {
            $nextSection->position = $nextSection->position - 1;
            $nextSection->save();
        }

        return 204;
    }

    public function getMaxPosition()
    {
        return Section::max('position');
    }

    public function reduceimg(Request $request)
    {
        $image = $this->imageManager->make(public_path('uploads/test/p01.jpg'));

        $destinationPathThumbnail = public_path('uploads/test/');
        $image->resize(100, 100);
        $image->save($destinationPathThumbnail . 'sm_p01.jpg');

        return 'success';
    }
}
