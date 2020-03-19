<?php

namespace App\ModelPages;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    use Translatable;
    protected $fillable = ['file'];
    public $translatedAttributes = ['name', 'keywords', 'description', 'content'];

    /*public function files()
    {
        return $this->hasMany('App\ModelPages\PagesFile', 'page_id');
    }*/


    public function translates()
    {
        return $this->hasMany('App\ModelPages\PagesTranslate', 'page_id');
    }
}
