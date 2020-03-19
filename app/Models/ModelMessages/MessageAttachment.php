<?php

namespace App\ModelMessages;

use Illuminate\Database\Eloquent\Model;

class MessageAttachment extends Model
{
    protected $fillable = [
        'file',
        'messsage_id'
    ];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
