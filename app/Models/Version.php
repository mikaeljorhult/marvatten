<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    use HasFactory;

    protected $fillable = ['application_id', 'name', 'is_current'];

    protected $casts = [
        'is_current' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($version) {
            if ($version->is_current) {
                static::where('application_id', $version->application_id)->update(['is_current' => 0]);
            }
        });

        static::updating(function ($version) {
            if ($version->is_current) {
                static::where('application_id', $version->application_id)->update(['is_current' => 0]);
            }
        });
    }

    public function application()
    {
        return $this->belongsTo(\App\Models\Application::class);
    }

    public function workstations()
    {
        return $this->belongsToMany(\App\Models\Workstation::class);
    }

    public function scopeCurrent($query)
    {
        return $query->whereTrue('current');
    }

    public function scopeOutdated($query)
    {
        return $query->whereNull('is_current');
    }

    public function isCurrent()
    {
        return $this->is_current === true;
    }
}
