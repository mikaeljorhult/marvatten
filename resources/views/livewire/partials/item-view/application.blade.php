<x-item-view.layout>
    <x-item-view.section key="overview">
        <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
        <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">
                    Licenses
                </dt>

                <dd class="mt-1 text-sm text-gray-900 @if($this->item->needsAttention) text-red-700 @endif">
                    {{ $this->item->usedSeats }} / {{ $this->item->totalSeats }}

                    @if($this->item->is_floating)
                        (floating)
                    @endif
                </dd>
            </div>

            @can('delete', $this->item)
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">
                        Manage
                    </dt>

                    <dd class="mt-1 text-sm text-gray-900">
                        <form action="{{ route('applications.destroy', [$this->item]) }}" method="POST">
                            @method('DELETE')
                            @csrf

                            <x-jet-button>Delete</x-jet-button>
                        </form>
                    </dd>
                </div>
            @endcan
        </dl>
    </x-item-view.section>

    <x-item-view.section key="versions">
        <div class="space-y-2">
            <form action="{{ route('applications.versions.store', [$this->item]) }}" method="post">
                @csrf

                <div class="space-y-1">
                    <x-jet-label for="name">Version</x-jet-label>

                    <p id="name_helper" class="sr-only">Add a new version</p>

                    <div class="flex">
                        <div class="flex-grow">
                            <x-jet-input
                                type="text"
                                name="name"
                                id="name"
                                aria-describedby="name_helper"
                                placeholder="Version number, e.g. 1.0.0"
                                value="{{ old('name') }}"
                                class="w-full"
                            />
                        </div>

                        <span class="ml-3">
                            <x-jet-button>
                                <x-heroicon-s-plus class="w-5 h-5 -ml-2 mr-1" />
                                <span>Add</span>
                            </x-jet-button>
                        </span>
                    </div>

                    <div class="py-2">
                        <x-jet-label for="is_current" class="inline-flex items-center">
                            <x-jet-input
                                type="checkbox"
                                name="is_current"
                                id="is_current"
                                value="1"
                                :checked="old('is_current')"
                            />

                            <span class="ml-1">Current version</span>
                        </x-jet-label>
                    </div>
                </div>
            </form>
        </div>

        @if($this->item->versions->count())
            <div class="flex flex-col py-4">
                <div class="overflow-hidden border-b border-gray-200 shadow-sm sm:rounded-lg">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name
                                </th>

                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($this->item->versions as $version)
                                <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $version->name }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @unless($version->is_current)
                                            <form action="{{ route('applications.versions.update', [$this->item, $version]) }}" method="post" class="inline-flex">
                                                @csrf
                                                @method('put')

                                                <input type="hidden" name="name" value="{{ $version->name }}" />
                                                <input type="hidden" name="is_current" value="1" />

                                                <x-jet-button>
                                                    @svg('heroicon-s-upload', 'h-2 w-2')
                                                </x-jet-button>
                                            </form>
                                        @endif

                                        <x-jet-button
                                            type="link"
                                            href="{{ route('applications.versions.edit', [$this->item, $version]) }}"
                                        >
                                            @svg('heroicon-s-pencil', 'h-2 w-2')
                                        </x-jet-button>

                                        <form action="{{ route('applications.versions.destroy', [$this->item, $version]) }}" method="post" class="inline-flex">
                                            @csrf
                                            @method('delete')

                                            <x-jet-button>
                                                @svg('heroicon-s-x', 'h-2 w-2')
                                            </x-jet-button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </x-item-view.section>

    @include('livewire.partials.item-view.comments')
</x-item-view.layout>
