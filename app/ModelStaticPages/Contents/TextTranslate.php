<?php

namespace App\ModelStaticPages\Contents;

use Illuminate\Database\Eloquent\Model;

class TextTranslate extends Model
{
    protected $fillable = [
        'id' ,
        'name',
        'description',
        'text_id',
        'locale',
    ];
}
