<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function content()
    {
        return $this->hasMany(Content::class);
    }

    public function genre()
    {
        return $this->hasMany(Genre::class);
    }
}
