<?php

namespace App\Models;

use App\Models\Traits\HasComments;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Application extends Model
{
    use HasFactory, HasRelationships, HasComments;

    protected $fillable = ['name', 'seats', 'is_floating'];

    protected $casts = [
        'is_floating' => 'boolean',
    ];

    public function workstations()
    {
        return $this->belongsToMany(\App\Models\Workstation::class);
    }

    public function versions()
    {
        return $this->hasMany(\App\Models\Version::class)
                    ->orderByRaw('LENGTH(name) DESC')
                    ->orderBy('name', 'DESC');
    }

    public function currentVersion()
    {
        return $this->hasOne(\App\Models\Version::class)->where('is_current', true);
    }

    public function scopeInstalledCount($query)
    {
        return $query->withCount([
            'installedVersions' => function ($query) {
                $query->select(\DB::raw('count(distinct workstations.id)'));
            },
        ]);
    }

    public function getTotalSeatsAttribute()
    {
        return $this->seats ?: 0;
    }

    public function getUsedSeatsAttribute()
    {
        return $this->installed_versions_count ?? $this->installedVersions->count();
    }

    public function getAvailableSeatsAttribute()
    {
        return $this->seats
            ? $this->seats - $this->usedSeats
            : 100000;
    }

    public function installedVersions()
    {
        return $this->hasManyDeep('App\Models\Workstation', ['App\Models\Version', 'version_workstation'])->distinct();
    }

    public function scopeNeedsAttention(Builder $query)
    {
        $query->installedCount()
            ->whereNotNull('seats')
            ->where('is_floating', false)
            ->groupBy('id')
            ->having('installed_versions_count', '>', \DB::raw('seats'));
    }

    public function getNeedsAttentionAttribute()
    {
        return $this->seats !== null
               && $this->is_floating === false
               && $this->seats < $this->usedSeats;
    }
}
