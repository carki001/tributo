<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use stdClass;

class AttachmentController extends Controller
{
    private $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(array('driver' => 'imagick'));
    }

    public function index()
    {
        $sections = Section::get();

        foreach ($sections as $sec) {
            $images = Attachment::where('model_name', 'section-gallery')->where('record_id', $sec->id)->get();

            foreach ($images as $img) {
                $file = new \stdClass();
                $file->name = $img->name;
                $file->path = $img->path;
                $file->file_extension = $img->file_extension;
                $file->file_type = $img->file_type;
                $file->file_size = $img->file_size;
                $img->file = $file;
            }

            $sec->images = $images;
            $sec->basic_uri = $sec->id . "-" . $sec->alias;
            $sec->isEditing = false;
        }

        return ['sections' => $sections];
    }

    public function store(Request $request)
    {
        if (isset($request->attachment_id) && !empty($request->attachment_id)) {
            $attachmentToDelete = Attachment::find($request->attachment_id);
            Storage::disk('public_uploads')->delete($attachmentToDelete->path . '/' . $attachmentToDelete->name);
            $attachmentToDelete->delete();
        }
        $attachment = new Attachment();
        $file = $request->file;

        $fileName = $file->getClientOriginalName();
        $fileName = preg_replace("([^\w\d\-_~,;.\[\]\(\)])", "-", $fileName);
        $fileName = ltrim($fileName, '.-');

        $attachment->name = $fileName;
        $attachment->path = $request->model_name . '/' . $request->section_id;
        $attachment->file_extension = $request->file('file')->extension();
        $attachment->file_type = $request->file('file')->getMimeType();
        $attachment->file_size = $request->file('file')->getSize();
        $attachment->record_id = $request->section_id;
        $attachment->model_name = $request->model_name;
        $attachment->save();

        Storage::disk('public_uploads')->putFileAs($request->model_name . '/' . $request->section_id, $file, $fileName, 'public');
        $filePath = public_path('uploads/' . $request->model_name . '/' . $request->section_id . '/' . $fileName);
        $fileFolder = public_path('uploads/' . $request->model_name . '/' . $request->section_id . '/');
        $this->reduceImageSize($filePath, $fileFolder, $fileName);

        return response()->json($attachment, 200);
    }

    public function delete($id)
    {
        $attachmentToDelete = Attachment::find($id);
        if ($attachmentToDelete) {
            Storage::disk('public_uploads')->delete($attachmentToDelete->path . '/' . $attachmentToDelete->name);
            $attachmentToDelete->delete();
        }

        return 'sucessful_deletion';
    }

    public function reduceImageSize($filePath, $fileFolder, $fileName)
    {
        $image = $this->imageManager->make($filePath);
        $originalHeight = $image->height();
        $originalWidth = $image->width();
        $newHeight = $originalHeight * 320 / $originalWidth;

        $destinationPathThumbnail = $fileFolder;
        $image->resize(320, $newHeight);
        $image->save($destinationPathThumbnail . 'small_' . $fileName);

        return 'success';
    }
}
