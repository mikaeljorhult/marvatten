<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ItemList extends Component
{
    public $model;
    public $items;
    public $count;

    public $search = '';

    public function mount($model)
    {
        $this->model = class_basename($model);
        $this->items = $this->getItems($model);
        $this->count = $this->items->count();
    }

    protected function getItems($model)
    {
        return $model::orderBy('name')->get();
    }

    public function getGroupsProperty()
    {
        return $this->items
            ->unless(empty($this->search), function ($collection) {
                return $collection->filter(function ($item, $key) {
                    return \Str::contains(
                        strtolower($item->name),
                        strtolower($this->search)
                    ) || \Str::contains(
                        strtolower($item->serial),
                        strtolower($this->search)
                    );
                });
            })
            ->mapToGroups(function ($item, $key) {
                return [strtoupper(substr($item->name, 0, 1)) => $item];
            });
    }

    public function render()
    {
        return view('livewire.item-list');
    }
}
