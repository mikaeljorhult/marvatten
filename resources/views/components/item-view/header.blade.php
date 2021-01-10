@props(['tabs', 'activeTab'])

<div class="flex items-center justify-between max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900 truncate">
        {{ $this->item->name }}
    </h1>

    <a href="{{ route(\Str::plural(strtolower(class_basename($this->item))).'.edit', [$this->item]) }}">
        @svg('heroicon-s-pencil', 'h-5 w-5 text-gray-600')
    </a>
</div>

<div class="mt-6 sm:mt-2 2xl:mt-5">
    <div class="border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                @foreach($tabs as $key => $label)
                    <x-item-view.tab
                        :key="$key"
                        :active="$activeTab === $key"
                    >
                        {{ $label }}
                    </x-item-view-tab>
                @endforeach
            </nav>
        </div>
    </div>
</div>
