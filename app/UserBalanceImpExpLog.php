<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBalanceImpExpLog extends Model
{
    public function user() {
        return $this->belongsTo('App\ModelUser\User', 'user_id');
    }
}
