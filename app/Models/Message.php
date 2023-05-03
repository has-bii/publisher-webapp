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
        'sender_id',
        'parent_id',
        'is_seen',
    ];

    public function message_receiver()
    {
        return $this->hasMany(MessageReceiver::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function message_child()
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }

    public function message_parent()
    {
        return $this->hasMany(Message::class, 'parent_id');
    }
}
