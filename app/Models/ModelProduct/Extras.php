<?php

namespace App\ModelProduct;

use Illuminate\Database\Eloquent\Model;

class Extras extends Model
{
    public function countries() {
        return $this->belongsTo('App\ModelCountry\Country', 'country_id');
    }
}
