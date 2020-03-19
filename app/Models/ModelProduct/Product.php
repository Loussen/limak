<?php

namespace App\ModelProduct;

use Illuminate\Database\Eloquent\Model;
use App\ModelCountry\Country;

class Product extends Model
{

    const PAID = 1;
    const NONE_PAID = 0;
    const ORDERED = 1;

    protected $guarded = ['id'];
    public function relUserProducts() {
        return $this->belongsTo('App\RelUserProduct', 'rel_user_product_id');
    }

    public function productsType() {
        return $this->belongsTo('App\ModelProduct\ProductType', 'product_type_id');
    }
    public function extras() {
        return $this->belongsTo('App\ModelProduct\Extras', 'extras_id');
    }

    public function invoices() {
        return $this->hasMany('App\Invoice', 'product_id')->where('invoices.active', '=', 1);
    }

    public function statuses() {
        return $this->belongsTo('App\Status', 'status_id');
    }

    public function users() {
        return $this->belongsTo('App\ModelUser\User', 'user_id');
    }

    public function relAdminsAcceptedProduct()
    {
        return $this->hasMany('App\RelAdminsAcceptedProduct', 'rel_user_product_id');
    }

    public function adminsAcceptedProduct()
    {
        return $this->belongsTo('App\Admins', 'admin_id');
    }

    public function productTypes() {
        return $this->belongsTo('App\ModelProduct\ProductType', 'product_type_id');
    }

    public function country(){
        return $this->belongsTo('App\ModelCountry\Country');
    }


}
