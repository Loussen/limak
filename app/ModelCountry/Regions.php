<?php

namespace App\ModelCountry;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Regions extends Model
{
    use Translatable;
    protected $table = 'regions';
    public $translatedAttributes = ['name'];

    public function translates()
    {
        return $this->hasMany('App\ModelCountry\CountryTranslate', 'country_id');
    }

    public function tariffs() {
        return $this->hasMany('App\ModelCountry\Regions', 'country_id');
    }
}
