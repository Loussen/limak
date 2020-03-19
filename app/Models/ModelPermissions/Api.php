<?php

namespace App\ModelPermissions;

use Illuminate\Database\Eloquent\Model;

class Api extends Model
{
    const API_METHODS = ['get', 'post', 'put', 'delete'];

    public function relRolesApis() {
        return $this->hasMany('App\ModelPermissions\RelRoleApi', 'api_id', 'id');

    }
}
