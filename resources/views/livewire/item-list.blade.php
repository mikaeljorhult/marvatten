<x-sidebar>
    <div
        class="px-6 pt-6 pb-4"
        x-data
    >
        <h2 class="text-lg font-medium text-gray-900">
            {{ \Str::plural($model) }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Search directory of {{ $count }} {{ strtolower(\Str::plural($model, $count)) }}
        </p>

        <form class="mt-6 flex space-x-4" action="#">
            <div class="flex-1 min-w-0">
                <label for="search" class="sr-only">Search</label>

                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        @svg('heroicon-o-search', 'h-5 w-5 text-gray-400')
                    </div>

                    <x-jet-input
                        type="search"
                        name="search"
                        id="search"
                        placeholder="Search"
                        class="w-full pl-10"
                        wire:model="search"
                        x-on:keydown.escape="$wire.set('search', null)"
                    />
                </div>
            </div>

            <button type="submit" class="inline-flex justify-center px-3.5 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                @svg('heroicon-o-filter', 'h-5 w-5 text-gray-400')
                <span class="sr-only">Search</span>
            </button>
        </form>
    </div>

    <nav class="flex-1 min-h-0 relative overflow-y-auto" aria-label="Directory">
        @foreach($this->groups as $character => $group)
            <div class="z-10 sticky top-0 border-t border-b border-gray-200 bg-gray-50 px-6 py-1 text-sm font-medium text-gray-500">
                <h3>{{ $character }}</h3>
            </div>

            <ul class="relative z-0 divide-y divide-gray-200">
                @foreach($group as $item)
                    @includeFirst(['livewire.partials.item-list.'.$this->model, 'livewire.partials.item-list.default'])
                @endforeach
            </ul>
        @endforeach
    </nav>
</x-sidebar>
