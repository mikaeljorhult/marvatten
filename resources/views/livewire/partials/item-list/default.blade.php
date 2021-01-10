<li>
    <div class="relative px-6 py-5 flex items-center space-x-3 hover:bg-gray-50 focus-within:ring-2 focus-within:ring-inset focus-within:ring-pink-500">
        <div class="flex-1 min-w-0">
            <a
                href="{{ route(strtolower(\Str::plural($this->model)) . '.show', [$item]) }}"
                class="focus:outline-none"
                wire:click.prevent="$emitTo('item-view', 'itemChanged', {{ $item->id }})"
            >
                <span class="absolute inset-0" aria-hidden="true"></span>

                <p class="text-sm font-medium text-gray-900">
                    {{ $item->name }}
                </p>

                <p class="text-sm text-gray-500 truncate">
                    &nbsp;
                </p>
            </a>

            @if($item->needsAttention)
                <div class="w-2 h-2 absolute top-5 right-4 bg-pink-500 rounded"></div>
            @endif
        </div>
    </div>
</li>
