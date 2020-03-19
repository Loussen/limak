<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserActivations extends Model
{
    public function user() {
        return $this->belongsTo('App\ModelUser\User', 'user_id');
    }
}
