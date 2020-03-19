<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fatura extends Model
{
    const MAX_WEIGHT = 145;
    protected $table = 'fatura';

    public function invoices() {
        return $this->hasMany('App\Invoice', 'fatura_id');
    }
}
