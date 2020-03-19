<?php

namespace App\ModelPermissions;

use Illuminate\Database\Eloquent\Model;

class RelRoleApi extends Model
{
    protected $fillable = ['api_id', 'role_id'];

    public function apis()
    {
        return $this->belongsTo('App\ModelPermissions\Api', 'api_id');
    }
}
