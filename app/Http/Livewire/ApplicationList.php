<?php

namespace App\Http\Livewire;

class ApplicationList extends ItemList
{
    protected function getItems($model)
    {
        return \App\Models\Application::installedCount()->orderBy('name')->get();
    }
}
