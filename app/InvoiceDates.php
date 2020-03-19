<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceDates extends Model
{
    protected $table = 'invoice_dates';
    protected $fillable = ['invoice_id', 'status_id', 'action_date'];

    const CREATED_AT = null;
    const UPDATED_AT = null;

    public function status() {
        return $this->belongsTo('App\Status','status_id','sid')->where("type","invoice");
    }

}
