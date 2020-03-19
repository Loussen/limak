<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Invoiceless extends Model
{
    protected $fillable = [
        'note',
        'user_uid',
        'date',
        'sent',
        'sent_date',
        'is_active'
    ];
    protected $casts = [
        'sent_date' => 'date:d.m.Y H:i'
    ];

    protected $table = 'invoicelesses';

    public function users() {
        return $this->belongsTo(User::class, 'uniqid');
    }

}
