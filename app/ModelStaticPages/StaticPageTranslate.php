<?php

namespace App\ModelStaticPages;

use Illuminate\Database\Eloquent\Model;

class StaticPageTranslate extends Model
{
    protected $fillable = [
        'id' ,
        'name',
        'description',
        'slug',
        'static_page_id',
        'locale'
    ];
}
