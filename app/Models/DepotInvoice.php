<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepotInvoice extends Model
{
    public function depot() {
        return $this->belongsTo('App\Depot', 'depot_id');
    }
}
