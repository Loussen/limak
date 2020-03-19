<?php

namespace App\ModelCountry;

use Illuminate\Database\Eloquent\Model;


class CountryTranslate extends Model
{
    protected $table = 'country_translates';
    protected $fillable = ['name'];
}
