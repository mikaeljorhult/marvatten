<x-app-layout>
    <x-slot name="sidebar">
        <livewire:item-list :model="\App\Models\User::class" />
    </x-slot>

    <livewire:item-view :class="\App\Models\User::class" :id="$user->id" />
</x-app-layout>
