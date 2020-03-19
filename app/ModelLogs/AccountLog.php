<?php

namespace App\ModelLogs;

use Illuminate\Database\Eloquent\Model;

class AccountLog extends Model
{
    const UPDATED_AT = null;
    protected $guarded = ['id'];

    public function admin()
    {
        return $this->belongsTo('App\Admins');
    }

    public function user()
    {
        return $this->belongsTo('App\ModelUser\User');
    }
}