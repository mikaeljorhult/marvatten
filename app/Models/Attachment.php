<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id', 'attachable_type', 'attachable_id'];

    protected static function booted()
    {
        static::creating(function ($attachment) {
            if (auth()->check()) {
                $attachment->user_id = auth()->id();
            }
        });

        static::deleting(function ($attachment) {
            Storage::delete($attachment->path);
        });
    }

    public function attachable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
