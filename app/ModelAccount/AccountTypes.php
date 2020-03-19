<?php

namespace App\ModelAccount;

use Illuminate\Database\Eloquent\Model;

class AccountTypes extends Model
{
    public $timestamps = false;

    public function accounts(){
        return $this->HasMany(Account::class,'type');
    }
}