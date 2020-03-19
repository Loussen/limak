<?php

namespace App\ModelCountry;

use Illuminate\Database\Eloquent\Model;


class RegionsTranslate extends Model
{
    protected $table = 'region_translates';
    protected $fillable = ['name'];
}
