<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['comment', 'user_id', 'commentable_type', 'commentable_id'];

    protected static function booted()
    {
        static::creating(function ($comment) {
            if (auth()->check()) {
                $comment->user_id = auth()->id();
            }
        });
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
