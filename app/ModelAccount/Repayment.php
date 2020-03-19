<?php

namespace App\ModelAccount;

use Illuminate\Database\Eloquent\Model;

class Repayment extends Model
{
    protected $guarded = [];

    const UPDATED_AT = NULL;

    protected $with = ['executor'];

    public function executor(){
        return $this->BelongsTo('App\Admins','executer','id');
    }
}
