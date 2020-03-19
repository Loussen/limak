<?php

namespace App\ModelUser;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public static $region_id;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'serial_number', 'address', 'gender','nationality' ,'birthdate', 'surname', 'pin', 'balance', 'uniqid', 'passport_date', 'citizenship'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function userContacts() {
        return $this->hasMany('App\ModelUser\UserContact', 'user_id');
    }

    //new
    public function products()
    {
        return $this->hasMany('App\ModelProduct\Product', 'user_id')->where('is_paid', '=', 1);
    }

    public function basket()
    {
        return $this->hasMany('App\ModelProduct\Product', 'user_id')->where('is_paid', '=', 0);
    }

    public function notOrderedProducts()
    {
        return $this->hasMany('App\ModelProduct\Product', 'user_id')->where('status_id', '=', 1)->where('region_id', '=', self::$region_id)->where('is_paid',1)->where('is_ordered','=',0);
    }

    public function orders()
    {
        return $this->hasMany('App\Invoice', 'user_id')->where('active', '=', 1)->where('status_id' , '=', 1);
    }
    public function foreignStock()
    {
        return $this->hasMany('App\Invoice', 'user_id')->where('active', '=', 1)->where('status_id' , '=', 2);
    }
    public function onTheWay()
    {
        return $this->hasMany('App\Invoice', 'user_id')->where('active', '=', 1)->where('status_id' , '=', 3);
    }

    public function stock()
    {
        return $this->hasMany('App\Invoice', 'user_id')->where('active', '=', 1)->where('status_id' , '=', 4);
    }



    public function invoices()
    {
        return $this->hasMany('App\Invoice', 'user_id')->where('active', '=', 1);
    }
}
