<?php

namespace App\Http\Livewire;

class WorkstationList extends ItemList
{
    protected function getItems($model)
    {
        return \App\Models\Workstation::withNeedsAttentionStatus()->orderBy('name')->get();
    }
}
