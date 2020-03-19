<?php

namespace App\Http\Controllers\Admin\StaticPages;

use App\ModelStaticPages\Contents\Media;
use App\ModelStaticPages\Contents\MediaFile;
use App\ModelStaticPages\Contents\Text;
use App\ModelStaticPages\ContentStaticPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ContentStaticPagesController extends Controller
{
    private $contentStaticPages;
    public function __construct(ContentStaticPage $contentStaticPages)
    {
        $this->contentStaticPages = $contentStaticPages;
    }

    /**
     * Get Generated Page
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function list($id) {
// dd($id);        
$response = [];
        $components = $this->contentStaticPages
            ->where('static_page_id', $id)
            ->orderBy('sort')
            ->get();
        if(count($components) > 0) {
            foreach ($components as $component) {
                $data = $this->modifyData($component);
                $data->type = $component->contentType->label;
                $response[] = $data;
            }
        }
        return response()->json($response, 200);
    }
    /**
     * Get Generated Page
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function bySlug($id) {
//dd($id);
        $response = [];
        $components = $this->contentStaticPages
            ->where('static_page_id', $id)
            ->orderBy('sort')
            ->get();

        if(count($components) > 0) {
            foreach ($components as $component) {
                $data = $this->modifyData($component);
                $data->type = $component->contentType->label;
                $response[] = $data;
            }
        }
        return $response;
    }

    /**
     * Facade
     * @param $component
     * @return null
     */
    private function modifyData($component) {
        switch($component->contentType->label) {
            case 'text':
                return (object)['data' => $this->text($component)];
                break;
            case 'media':
                return $this->media($component);
                break;
        }
    }

    /**
     * Provide Text Data
     * @param $component
     * @return
     */
    private function text($component) {
        $text = new Text();
        return $text
            ->join('text_translates', function ($join) {
                $join->on('texts.id', '=', 'text_translates.text_id');
                })
            ->where('text_id', $component->content_id)
            ->get();
//        return $text
//            ->join('text_translates', function ($join) {
//                $join->on('texts.id', '=', 'text_translates.text_id')
//                    ->where('text_translates.locale', '=', 'az');
//                })
//            ->findOrFail($component->content_id);
    }

    /**
     * Provide Media Data
     * @param $component
     * @return null
     */
    private function media($component) {
        $media = new Media();
        $mediaData = $media->findOrFail($component->content_id);
        $mediaFile = new MediaFile();
        $mediaData->files = $mediaFile->where('media_id' , $mediaData->id)
            ->join('media_file_translates', function ($join) {
                $join->on('media_files.id', '=', 'media_file_translates.media_file_id')
                    ->where('media_file_translates.locale', '=', 'az');
                })
            ->get();
        if (count($mediaData->files) > 0) {
            foreach ($mediaData->files as $file) {
                if($file->type === 'photo') {
                    $file->file = asset(Storage::url($file->file));
                }
            }
        }
        return $mediaData;
    }

    /**
     * Insert Generated page
     * @return \Illuminate\Http\JsonResponse
     */
    public function insert(Request $request) {
//        dd($request->section[0]['value']['az']);
        if (count($request->section) > 0) {
            foreach ($request->section as $index => $section) {
                $componentId = null;
                $section = (object)$section;
                switch ($section->contentTypeLabel) {
                    case 'text':
                        $componentId = app('App\Http\Controllers\Admin\StaticPages\TextsController')->insert($section);
                    break;
                    case 'media':
                        $componentId = app('App\Http\Controllers\Admin\StaticPages\MediasController')->insert($section);
                }

                $csp = new ContentStaticPage();
                $csp->sort = ($index + 1);
                $csp->static_page_id = (int)$request->id;
                $csp->content_id = (int)$componentId->id;
                $csp->content_type_id = (int)$section->contentTypeId;
                $csp->save();
            }
            return response()->json('ok', 201);
        } else {
            return response()->json('Seçim edilməmişdir', 500);
        }
//        return response()->json($list, 200);
    }
}
