<?php

namespace App\ModelStaticPages;

use Illuminate\Database\Eloquent\Model;

class ContentType extends Model
{
    protected $fillable = [
        'id',
        'label'
    ];
}
