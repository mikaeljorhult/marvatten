@empty($this->itemId)
    @include('livewire.partials.item-view.empty-'.$this->model)
@else
    @includeFirst(['livewire.partials.item-view.'.$this->model, 'livewire.partials.item-view.item'])
@endempty
