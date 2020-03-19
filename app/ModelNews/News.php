<?php

namespace App\ModelNews;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use Translatable;
    protected $fillable = ['file'];
    public $translatedAttributes = ['name', 'keywords', 'description', 'content'];

    public function files()
    {
        return $this->hasMany('App\ModelNews\NewsFile', 'news_id');
    }


    public function translates()
    {
        return $this->hasMany('App\ModelNews\NewsTranslate', 'news_id');
    }
}
