<?php

namespace App\ModelPermissions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class TrAdmin extends Authenticatable
{
    use Notifiable;
    protected $guard = 'tr';
    public $table = 'admins';

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
