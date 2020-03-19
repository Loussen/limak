<?php

namespace App\ModelPermissions;

use Illuminate\Database\Eloquent\Model;

class RelAdminRole extends Model
{
    public function relRole() {
        return $this->belongsTo('App\ModelPermissions\Role', 'role_id');
    }

    public function admins() {
        return $this->belongsTo('App\ModelPermissions\Admin', 'admin_id');
    }
}
