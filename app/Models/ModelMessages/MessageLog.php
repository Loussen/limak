<?php

namespace App\ModelMessages;

use Illuminate\Database\Eloquent\Model;

class MessageLog extends Model
{
    protected $fillable = ['status', 'message_id'];
    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
