<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admins extends Model
{
    const PRODUCT_REJECTION_STATUS = 4;
    const PRODUCT_REFUSAL_STATUS = 5;
    const INVOICE_OK_STATUS = 1;
    const TRANSACTION_OK_STATUS = 7;
    
    protected $table = 'admins';
}
