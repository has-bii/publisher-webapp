<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'body',
        'send_date',
        'sender_id',
        'parent_id',
        'is_seen',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function message_reciever()
    {
        return $this->hasOne(MessageReciever::class);
    }

    public function message_parent()
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }

    public function message_child()
    {
        return $this->hasMany(Message::class, 'parent_id');
    }
}
