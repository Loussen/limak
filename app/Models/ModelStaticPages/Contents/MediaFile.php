<?php

namespace App\ModelStaticPages\Contents;

use Illuminate\Database\Eloquent\Model;

class MediaFile extends Model
{
    protected $fillable = [
        'id',
        'name',
        'alt',
        'media_file_id',
        'locale'
    ];
}
