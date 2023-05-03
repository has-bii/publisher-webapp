<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageReceiver extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = [
        'receiver_id',
        'message_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
