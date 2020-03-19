<?php

namespace App\ModelPermissions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;
    protected $guard = 'admin';

    protected $fillable = [
        'name', 'surname', 'username', 'password',
    ];

    protected $hidden = [
        'password',
    ];
    public function relAdminRoles() {
        return $this->hasMany('App\ModelPermissions\RelAdminRole', 'admin_id', 'id');
    }
}
