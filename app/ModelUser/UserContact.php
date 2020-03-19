<?php

namespace App\ModelUser;

use Illuminate\Database\Eloquent\Model;

class UserContact extends Model
{
    protected $fillable = [
        'name', 'user_id'
    ];
    public function user() {
        return $this->belongsTo('App\ModelUser\User', 'user_id');
    }
}
