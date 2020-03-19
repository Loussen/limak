<?php

namespace App\ModelCountry;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use Translatable;
    protected $table = 'countries';
    public $translatedAttributes = ['name'];

    public function translates()
    {
        return $this->hasMany('App\ModelCountry\CountryTranslate', 'country_id');
    }

    public function tariffs() {
        return $this->hasMany('App\ModelCountry\CountryTariff', 'country_id');
    }
}
