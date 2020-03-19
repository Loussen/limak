<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductDates extends Model
{
    protected $table = 'product_dates';
    protected $fillable = ['product_id', 'status_id', 'action_date'];

    const CREATED_AT = null;
    const UPDATED_AT = null;
}
