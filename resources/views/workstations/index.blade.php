<x-app-layout>
    <x-slot name="sidebar">
        <livewire:workstation-list :model="\App\Models\Workstation::class" />
    </x-slot>

    <livewire:item-view
        :class="\App\Models\Workstation::class"
        :tabs="['overview' => 'Overview', 'applications' => 'Applications', 'networks' => 'Networks', 'comments' => 'Comments', 'attachments' => 'Attachments']"
    />
</x-app-layout>
