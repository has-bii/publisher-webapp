<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cover',
        'price',
        'file',
        'type_id',
        'genre_id',
        'author_id',
        'editor_id',
        'status_id',
        'publisher_id',
        'published_date',
        'uploaded_date',
    ];

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function user_author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function user_editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function announcement()
    {
        return $this->hasMany(Announcement::class);
    }
}
