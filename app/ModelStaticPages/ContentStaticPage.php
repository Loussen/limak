<?php

namespace App\ModelStaticPages;

use Illuminate\Database\Eloquent\Model;

class ContentStaticPage extends Model
{
    protected $fillable = [
        'id',
        'sort',
        'static_page_id',
        'content_id',
        'content_type_id'
    ];

    public function contentType()
    {
        return $this->belongsTo(ContentType::class);
    }
}
