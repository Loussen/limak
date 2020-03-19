<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPromoCodes extends Model
{
    protected $table = 'user_promo_codes';
    protected $guarded = ['id'];
    const UPDATED_AT = null;
    const CREATED_AT = 'create_date';

    public function campaign() {
        return $this->belongsTo('App\Campaigns', 'campaign_id');
    }

}
