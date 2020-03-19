<?php
/**
 * Created by PhpStorm.
 * User: Rashad
 * Date: 25.11.2018
 * Time: 19:04
 */

namespace App\ModelProduct;


use Illuminate\Database\Eloquent\Model;

class ProductRejection extends Model
{
    public function buyer() {
        return $this->belongsTo('App\ModelPermissions\Admin', 'from_admin_id');
    }
    public function relUserProducts() {
        return $this->belongsTo('App\RelUserProduct', 'rel_user_product_id');
    }

    public function products() {
        return $this->belongsTo('App\ModelProduct\Product', 'product_id');
    }
}
