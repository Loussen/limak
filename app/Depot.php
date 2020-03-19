<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Depot extends Model
{
    public function depotInvoice()
    {
        return $this->hasMany( DepotInvoice::class);
    }
}
