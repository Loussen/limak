<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RelUserProduct extends Model
{
    const PAID = 1;
    const NONE_PAID = 0;
    const ORDERED = 1;

    public function users() {
        return $this->belongsTo('App\ModelUser\User', 'user_id');
    }

    public function products()
    {
        return $this->hasMany('App\ModelProduct\Product', 'rel_user_product_id');
    }

    public function relAdminsAcceptedProduct()
    {
        return $this->hasMany('App\RelAdminsAcceptedProduct', 'rel_user_product_id');
    }


    public function statuses() {
        return $this->belongsTo('App\Status', 'status_id');
    }
}
