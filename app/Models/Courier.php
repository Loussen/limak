<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    const OK_TYPE = 1;
    const NON_TYPE = 0;
    const COURIER_PRICE_NORMAL = 3;
    const COURIER_PRICE_FAST = 5;

    public function invoices () {
        return $this->hasMany('App\Invoice', 'courier_id');
    }

    public function users () {
        return $this->belongsTo('App\ModelUser\User', 'user_id');
    }
}
