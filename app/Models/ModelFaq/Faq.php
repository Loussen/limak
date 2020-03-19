<?php

namespace App\ModelFaq;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use Translatable;
    protected $table = 'faqs';
    public $translatedAttributes = ['answer', 'question'];

    public function translates()
    {
        return $this->hasMany('App\ModelFaq\FaqTranslate', 'faq_id');
    }
}
