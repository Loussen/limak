<?php

namespace App\ModelCountry;

use Illuminate\Database\Eloquent\Model;


class RegionTranslate extends Model
{
    protected $table = 'region_translates';
    protected $fillable = ['name'];
}
