<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    const STATUS_FINISHED=5;

    public function invoiceStatus() {
        return $this->belongsTo('App\Status','status_id','sid')->where("type","invoice");
    }

    public function relUserProducts() {
        return $this->belongsTo('App\RelUserProduct', 'rel_user_product_id');
    }

    public function products() {
        return $this->belongsTo('App\ModelProduct\Product', 'product_id');
    }
    public function depotInvoice() {
        return $this->belongsTo('App\DepotInvoice', 'id', 'invoice_id');
    }
    public function courier() {
        return $this->belongsTo('App\Courier', 'courier_id');
    }
    public function returns() {
        return $this->belongsTo('App\Returns', 'return_id');
    }
    public function users() {
        return $this->belongsTo('App\ModelUser\User', 'user_id');
    }

    public function dates() {
        return $this->hasMany('App\InvoiceDates');
    }

    public function packages(){
        return $this->hasMany('App\Invoice', 'package_id','package_id');
    }
}
