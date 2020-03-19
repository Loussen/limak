<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    const PRODUCT_REJECTION_STATUS = 4;
    const PRODUCT_REFUSAL_STATUS = 5;
    const INVOICE_OK_STATUS = 1;
    const TRANSACTION_OK_STATUS = 7;
    
    protected $table = 'statuses';
}
