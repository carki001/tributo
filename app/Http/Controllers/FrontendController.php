<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Attachment;
use App\Models\Section;
use Attribute;

class FrontendController extends Controller
{
    public function index()
    {
        return view('frontend');
    }

    public function getHomeData()
    {
        $generalDataRaw = Setting::where('key', 'LIKE', 'general_%')->get();
        $footerDataRaw = Setting::where('key', 'LIKE', 'footer_%')->get();
        $headerDataRaw = Setting::where('key', 'LIKE', 'header_%')->get();
        $headerImage = Attachment::where('model_name', 'header-image')->first();

        $generalData = $this->restructureSettings($generalDataRaw);
        $footerData = $this->restructureSettings($footerDataRaw);
        $headerData = $this->restructureSettings($headerDataRaw);

        $pageData = [
            'generalData' => $generalData,
            'footerData' => $footerData,
            'headerData' => $headerData,
            'headerImage' => $headerImage,
        ];

        return $pageData;
    }

    public function restructureSettings($settings)
    {
        $newSettings = [];
        foreach ($settings as $sett) {
            $newSettings[$sett->key] = $sett->value;
        }
        return $newSettings;
    }

    public function getSections()
    {
        $sections = Section::orderBy('position', 'asc')->get();
        foreach ($sections as $sec) {
            $image = Attachment::where('model_name', 'home-section-image')->where('record_id', $sec->id)->first();
            $sec->image = $image;
        }
        $titleModel = Setting::where('key', 'content_title')->first();
        $title = $titleModel->value;

        return ['sections' => $sections, 'contentTile' => $title];
    }

    public function getSectionDetails($alias)
    {
        $aliasExploded = explode('-', $alias);
        $section_id = $aliasExploded[0];
        $section = Section::find($section_id);

        $gallery = Attachment::where('model_name', 'section-gallery')->where('record_id', $section_id)->get();

        foreach ($gallery as $image) {
            $image->src = "/uploads/" . $image->path . "/" . $image->name;
            $image->thumbnail = "/uploads/" . $image->path . "/" . "small_" . $image->name;
            $image->alt = $image->name;
        }

        $section->gallery = $gallery;

        $galleryContentTitleQuery = Setting::where('key', 'gallery_content_title')->first();
        $galleryContentTitle = $galleryContentTitleQuery->value;

        return ['section' => $section, 'galleryContentTitle' => $galleryContentTitle];
    }
}
