<?php

namespace App\ModelMessages;

use App\ModelUser\User;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
      'subject',
      'message',
      'status',
      'has_attachment',
      'from_user_id',
      'to_user_id',
      'category_id'
    ];

    public function messageData()
    {
        return $this->belongsTo(Message::class);
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    public function category()
    {
        return $this->belongsTo(MessageCategory::class);
    }
}
