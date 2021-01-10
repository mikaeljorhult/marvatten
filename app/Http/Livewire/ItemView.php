<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ItemView extends Component
{
    public $class;
    public $itemId;
    public $tabs;

    protected $listeners = ['itemChanged'];

    public function mount($class, $id = null, $tabs = null)
    {
        $this->class  = $class;
        $this->itemId = $id;
        $this->tabs = $tabs ?? ['overview' => 'Overview'];
    }

    public function itemChanged($id)
    {
        $this->itemId = $id;
        $this->emit('url-changed', $this->generateCurrentUrl());
    }

    public function getItemProperty()
    {
        return $this->class::find($this->itemId);
    }

    public function getModelProperty()
    {
        return strtolower(class_basename($this->class));
    }

    public function getActiveTabProperty()
    {
        return session('tab', array_key_first($this->tabs));
    }

    private function generateCurrentUrl()
    {
        $parameters = [$this->itemId];

        if ($this->activeTab != array_key_first($this->tabs)) {
            $parameters['tab'] = $this->activeTab;
        }

        return route(\Str::plural($this->model) . '.show', $parameters);
    }

    public function render()
    {
        return view('livewire.item-view');
    }
}
