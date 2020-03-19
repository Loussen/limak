<?php
/**
 * Created by PhpStorm.
 * User: Rashad
 * Date: 03.12.2018
 * Time: 0:44
 */

namespace App\ModelCountry;


use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class CountryTariff extends Model
{
    use Translatable;
    public $translatedAttributes = ['name'];

    public function translates() {
        return $this->hasMany('App\ModelCountry\CountryTariffTranslate', 'country_tariff_id');
    }
}