<?php

namespace App\ModelFaq;

use Illuminate\Database\Eloquent\Model;

class FaqTranslate extends Model
{
    protected $table = 'faq_translates';
    protected $fillable = ['answer', 'question', 'faq_id'];
}
