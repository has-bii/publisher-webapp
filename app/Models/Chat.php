<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_message_id',
    ];

    public function last_message()
    {
        return $this->belongsTo(Message::class, 'last_message_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'message_id');
    }

    public function participants()
    {
        return $this->hasMany(Participant::class, 'chat_id');
    }
}
