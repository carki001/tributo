<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        $ret = [];

        foreach ($settings as $se) {
            $ret[] = [
                'key' => $se->key,
                'value' => is_numeric($se->value) ? (float) $se->value : $se->value
            ];
        }

        return response()->json($ret, 200);
    }

    public function update(Request $request, Setting $setting)
    {
        if (Setting::where('key', '=', $request->key)->exists()) {
            $count = Setting::where('key', $request->key)->update(['value' => $request->value ? $request->value : '']);
        } else {
            $count = Setting::create($request->all());
        }

        return response()->json($count, 200);
    }

    public function getHeaderImages()
    {
        $headerImage = Attachment::where('model_name', 'header-image')->first();
        $headerSectionImage = Attachment::where('model_name', 'section-header-image')->first();

        return ['headerImage' => $headerImage, 'headerSectionImage' => $headerSectionImage];
    }

    public function uploadHeaderImage(Request $request)
    {

        $attachmentToDelete = Attachment::where('model_name', $request->model_name)->first();
        if ($attachmentToDelete) {
            Storage::disk('public_uploads')->delete($attachmentToDelete->path . '/' . $attachmentToDelete->name);
            $attachmentToDelete->delete();
        }

        $attachment = new Attachment();
        $file = $request->file;

        $fileName = $file->getClientOriginalName();
        $fileName = preg_replace("([^\w\d\-_~,;.\[\]\(\)])", "-", $fileName);
        $fileName = ltrim($fileName, '.-');

        $attachment->name = $fileName;
        $attachment->path = $request->model_name;
        $attachment->file_extension = $request->file('file')->extension();
        $attachment->file_type = $request->file('file')->getMimeType();
        $attachment->file_size = $request->file('file')->getSize();
        $attachment->model_name = $request->model_name;
        $attachment->save();

        Storage::disk('public_uploads')->putFileAs($request->model_name . '/' . $request->section_id, $file, $fileName, 'public');

        return response()->json($attachment, 200);
    }

    public function delete($model_name)
    {
        $attachmentToDelete = Attachment::where('model_name', $model_name)->first();
        if ($attachmentToDelete) {
            Storage::disk('public_uploads')->delete($attachmentToDelete->path . '/' . $attachmentToDelete->name);
            $attachmentToDelete->delete();
        }

        return 'sucessful_deletion';
    }
}
