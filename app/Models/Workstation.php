<?php

namespace App\Models;

use App\Models\Traits\HasAttachments;
use App\Models\Traits\HasComments;
use App\Models\Traits\HasMetas;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workstation extends Model
{
    use HasFactory, HasComments, HasAttachments, HasMetas;

    protected $fillable = ['name', 'serial'];

    public function applications()
    {
        return $this->belongsToMany(\App\Models\Application::class)
                    ->orderBy('name', 'asc');
    }

    public function versions()
    {
        return $this->belongsToMany(\App\Models\Version::class);
    }

    public function networkAdapters()
    {
        return $this->hasMany(\App\Models\NetworkAdapter::class)
                    ->orderBy('name', 'asc');
    }

    public function scopeWithNeedsAttentionStatus(Builder $query)
    {
        $query->addSelect([
            'installed_applications' => function ($query) {
                $query
                    ->select(\DB::raw('GROUP_CONCAT(versions.application_id)'))
                    ->from('versions')
                    ->join('version_workstation', 'versions.id', '=', 'version_workstation.version_id')
                    ->whereColumn('workstations.id', 'version_workstation.workstation_id');
            },
            'requested_applications' => function ($query) {
                $query
                    ->select(\DB::raw('GROUP_CONCAT(applications.id)'))
                    ->from('applications')
                    ->join('application_workstation', 'applications.id', '=', 'application_workstation.application_id')
                    ->whereColumn('workstations.id', 'application_workstation.workstation_id');
            },
            'outdated' => function ($query) {
                $query
                    ->select(\DB::raw('GROUP_CONCAT(versions.id)'))
                    ->from('versions')
                    ->join('version_workstation', 'versions.id', '=', 'version_workstation.version_id')
                    ->whereColumn('workstations.id', 'version_workstation.workstation_id')
                    ->whereNull('is_current');
            },
        ]);
    }

    public function scopeNeedsAttention(Builder $query)
    {
        $query->when($this->hasNeedsAttentionScope($query), function ($query) {
            $query->withNeedsAttentionStatus();
        })
              ->havingRaw('installed_applications IS NULL AND requested_applications IS NOT NULL')
              ->orHavingRaw('installed_applications != requested_applications')
              ->orHavingRaw('outdated IS NOT NULL');
    }

    public function getNeedsAttentionAttribute()
    {
        return ($this->installed_applications == null && $this->requested_applications != null)
               || $this->installed_applications != $this->requested_applications
               || $this->outdated != null;
    }

    private function hasNeedsAttentionScope(Builder $query)
    {
        $columns = $query->getQuery()->columns;

        return is_null($columns)
               || count(preg_grep('/installed_applications/', $columns)) === 0;
    }
}
