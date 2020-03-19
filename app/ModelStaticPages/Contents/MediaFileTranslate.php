<?php

namespace App\ModelStaticPages\Contents;

use Illuminate\Database\Eloquent\Model;

class MediaFileTranslate extends Model
{
    protected $fillable = [
        'id',
        'file',
        'type',
        'media_id',
        'locale'
    ];
}
