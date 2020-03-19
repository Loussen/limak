<?php

namespace App\ModelPermissions;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const ADMIN_ACCOUNTANT = 6;
    protected $fillable = ['name'];


}
