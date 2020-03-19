<?php

namespace App\ModelNews;

use Illuminate\Database\Eloquent\Model;

class NewsTranslate extends Model
{
    //
    protected $fillable = ['name', 'keywords', 'description', 'content', 'news_id'];
}
