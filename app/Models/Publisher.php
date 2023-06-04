<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function content()
    {
        return $this->hasMany(Content::class);
    }

    public function announcement()
    {
        return $this->hasMany(Announcement::class);
    }
}
