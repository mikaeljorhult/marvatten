<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkAdapter extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'mac_address', 'workstation_id'];

    public function setMacAddressAttribute($value)
    {
        $this->attributes['mac_address'] = str_replace(':', '', $value);
    }

    public function getMacAddressAttribute($value)
    {
        return join(':', str_split($value, 2));
    }

    public function workstation()
    {
        return $this->belongsTo(\App\Models\Workstation::class);
    }
}
