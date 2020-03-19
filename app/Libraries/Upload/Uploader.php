<?php

namespace App\Libraries\Upload;

class Uploader
{
    public static function upload($input_name, $path, $file_name_prefix = null, $multiple = false, $returnOnlyFileName = false)
    {
        if ($multiple == false) {

            $file      = is_string($input_name) ? request()->file($input_name) : $input_name;
            $ext        = $file->guessClientExtension();
            $filename   = $file_name_prefix != null ? $file_name_prefix . "_" . uniqid() . ".{$ext}" : "file_" . uniqid() . ".{$ext}";

            $file->storeAs($path, $filename);

            if ($returnOnlyFileName) {
                return $filename;
            }

            return $path . $filename;

        } else {

            $paths = [];
            $files = request()->file($input_name);

            foreach ($files as $file)
            {
                $ext        = $file->guessClientExtension();
                $filename   = $file_name_prefix != null ? $file_name_prefix . "_" . uniqid() . ".{$ext}" : "file_" . uniqid() . ".{$ext}";

                $file->storeAs($path, $filename);

                array_push($paths, $path . $filename);
            }

            return $paths;
        }
    }
}
