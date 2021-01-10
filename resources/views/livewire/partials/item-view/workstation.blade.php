<x-item-view.layout>
    <x-item-view.section key="overview">
        <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">
                    Serial Number
                </dt>

                <dd class="mt-1 text-sm text-gray-900">
                    {{ $this->item->serial }}
                </dd>
            </div>

            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">
                    Requested applications
                </dt>

                <dd class="mt-1 text-sm text-gray-900">
                    {{ $this->item->applications->count() }}
                </dd>
            </div>

            @foreach($this->item->metas as $meta)
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">
                        {{ $meta->label }}
                    </dt>

                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $meta->value }}
                    </dd>
                </div>
            @endforeach

            @can('delete', $this->item)
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">
                        Manage
                    </dt>

                    <dd class="mt-1 text-sm text-gray-900">
                        <form action="{{ route('workstations.destroy', [$this->item]) }}" method="POST">
                            @method('DELETE')
                            @csrf

                            <x-jet-button>Delete</x-jet-button>
                        </form>
                    </dd>
                </div>
            @endcan
        </dl>
    </x-item-view.section>

    <x-item-view.section key="applications">
        <div class="space-y-2">
            <form action="{{ route('workstations.applications.store', [$this->item]) }}" method="post">
                @csrf

                <div class="space-y-1">
                    <x-jet-label for="application_id">Assign a new application</x-jet-label>

                    <p id="application_id_helper" class="sr-only">Assign an application</p>

                    <div class="flex">
                        <div class="flex-grow">
                            <select
                                name="application_id"
                                id="application_id"
                                aria-describedby="application_id_helper"
                                placeholder="Assign an application"
                                class="w-full block sm:text-sm border-gray-300 focus:ring-pink-500 focus:border-pink-500 rounded-md shadow-sm"
                            >
                                @foreach(\App\Models\Application::orderBy('name', 'asc')->get() as $application)
                                    @continue($this->item->applications->contains($application))

                                    <option
                                        value="{{ $application->id }}"
                                        {{ $application->id === old('application_id') ? 'checked' : '' }}
                                    >{{ $application->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <span class="ml-3">
                            <x-jet-button>
                                <x-heroicon-s-plus class="w-5 h-5 -ml-2 mr-1" />
                                <span>Add</span>
                            </x-jet-button>
                        </span>
                    </div>
                </div>
            </form>
        </div>

        @if($this->item->applications->count())
            <div class="flex flex-col py-4">
                <div class="overflow-hidden border-b border-gray-200 shadow-sm sm:rounded-lg">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name
                                </th>

                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Versions
                                </th>

                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($this->item->applications as $application)
                                @php($installedVersions = $this->item->versions->intersect($application->versions))

                                <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }}">
                                    <td class="align-baseline px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $application->name }}
                                    </td>

                                    <td class="align-baseline px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($installedVersions->count() > 0)
                                            <ul>
                                                @foreach($installedVersions as $version)
                                                    <li
                                                        @unless($version->isCurrent())
                                                            class="text-red-700"
                                                        @endif
                                                    >{{ $version->name }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-red-700">Not installed</span>
                                        @endif
                                    </td>

                                    <td class="align-baseline px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <x-jet-button
                                            type="link"
                                            href="{{ route('workstations.applications.edit', [$this->item, $application]) }}"
                                        >
                                            @svg('heroicon-s-pencil', 'h-2 w-2')
                                        </x-jet-button>

                                        <form action="{{ route('workstations.applications.destroy', [$this->item, $application]) }}" method="post" class="inline-flex">
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

    @include('livewire.partials.item-view.networks')
    @include('livewire.partials.item-view.comments')
    @include('livewire.partials.item-view.attachments')
</x-item-view.layout>
