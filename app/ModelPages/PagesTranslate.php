<?php

namespace App\ModelPages;

use Illuminate\Database\Eloquent\Model;

class PagesTranslate extends Model
{
    //
    protected $fillable = ['name', 'keywords', 'description', 'content', 'page_id'];
}
