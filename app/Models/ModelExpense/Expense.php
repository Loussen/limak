<?php

namespace App\ModelExpense;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $guarded = ['id'];
    const UPDATED_AT = NULL;
    const CREATED_AT = 'date';
}
