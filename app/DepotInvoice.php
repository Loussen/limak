<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepotInvoice extends Model
{
    public function depot() {
        return $this->belongsTo('App\Depot', 'depot_id');
    }
}
