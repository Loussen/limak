<?php

namespace App\Models;


use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    use Translatable;
    public $translatedAttributes = ['value', 'answer'];

    public function translates()
    {
        return $this->hasMany('App\Models\QuestionsTranslate', 'questions_id');
    }
}
