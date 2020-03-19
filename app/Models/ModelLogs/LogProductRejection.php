<?php

namespace App\ModelLogs;

use App\ModelPermissions\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LogProductRejection extends Model
{
    protected $appends = ['from_admin'];
    public function getFromAdminsAttribute () {
//        return $admins =  Admin::find($this->from_admin_id);
        return $admins =  DB::table('log_product_rejections')->where('id', $this->id)->sum('id');
    }
}
