<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'content_id',
        'publisher_id'
    ];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }
}
