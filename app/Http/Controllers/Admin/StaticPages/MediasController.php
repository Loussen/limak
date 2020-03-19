<?php

namespace App\Http\Controllers\Admin\StaticPages;

use App\Libraries\Upload\Uploader;
use App\ModelStaticPages\Contents\Media;
use App\ModelStaticPages\Contents\MediaFile;
use App\ModelStaticPages\Contents\MediaFileTranslate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class MediasController extends Controller
{
    /**
     * MediasController constructor.
     * @param Media $media
     */
    public function __construct(Media $media)
    {
        $this->medias = $media;
    }

    /**
     * @param Request $request
     * @return Media
     */
    public function insert($data)
    {
        $media = new Media();
        $media->save();
        if ($data->subtype === 'photo') {
            if (count($data->value) > 0) {
                foreach ($data->value as $file) {
                    $file = (object)$file;
                    $mediaFile = new MediaFile();
                    $mediaFile->media_id = $media->id;
                    $mediaFile->type = $data->subtype;
                        $mediaFile->file = Uploader::upload($file->file, 'public/uploads/static-pages/', 'sp');
                    $mediaFile->save();

                    $mediaFileTranslate = new MediaFileTranslate();
                    $mediaFileTranslate->name = $file->name;
                    $mediaFileTranslate->media_file_id = $mediaFile->id;
                    $mediaFileTranslate->locale = 'az';
                    $mediaFileTranslate->save();
                }
            }
        } else {
            $mediaFile = new MediaFile();
            $mediaFile->media_id = $media->id;
            $mediaFile->type = $data->subtype;
            $mediaFile->file = $data->value;
            $mediaFile->save();

            $mediaFileTranslate = new MediaFileTranslate();
            $mediaFileTranslate->name = $data->name;
            $mediaFileTranslate->media_file_id = $mediaFile->id;
            $mediaFileTranslate->locale = 'az';
            $mediaFileTranslate->save();
        }
        return $media;
    }

    public function update(Request $request)
    {
        if($request['subtype'] === 'video') {
            $mediaFile = MediaFile::findOrFail($request->media_file_id);
            $mediaFile->file = $request->value['link'];
            $mediaFile->update();

            $mediaFileTranslate = MediaFileTranslate::findOrFail($request->componentId);
            $mediaFileTranslate->name = $request->value['name'];
            $mediaFileTranslate->update();
        } else if($request['subtype'] === 'image') {
            $media = Media::findOrFail($request['media_id']);
            $mediaFiles = MediaFile::where('media_id' , $media->id)->get();
            if (count($mediaFiles) > 0) {
                foreach ($mediaFiles as $mediaFile) {
                    Storage::delete($mediaFile->file);
                    $mediaFile->delete();
                }
            }
            if(count($request['file']) > 0) {
                foreach ($request['file'] as $value) {
                    $mediaFile = new MediaFile();
                    $mediaFile->media_id = $media->id;
                    $mediaFile->type = 'photo';
                    $mediaFile->file = Uploader::upload($value['file'], 'public/uploads/static-pages/', 'sp');
                    $mediaFile->save();

                        $mediaFileTranslate = new MediaFileTranslate();
                        $mediaFileTranslate->name = !empty($value['name']) ? $value['name'] : '';
                        $mediaFileTranslate->media_file_id = $mediaFile->id;
                        $mediaFileTranslate->locale = 'az';
                        $mediaFileTranslate->save();

                }
            }
        }

        return response()->json('ok', 200);
    }
}
