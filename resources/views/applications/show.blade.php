<x-app-layout>
    <x-slot name="sidebar">
        <livewire:application-list :model="\App\Models\Application::class" />
    </x-slot>

    <livewire:item-view
        :class="\App\Models\Application::class"
        :id="$application->id"
        :tabs="['overview' => 'Overview', 'versions' => 'Versions', 'comments' => 'Comments']"
    />
</x-app-layout>
