<x-app-layout>
    <x-slot name="sidebar">
        <livewire:workstation-list :model="\App\Models\Workstation::class" />
    </x-slot>

    <article class="py-6">
        <div class="flex items-center justify-between max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900 truncate">
                Edit {{ $application->name }} on {{ $workstation->name }}
            </h1>

            <a href="{{ route('workstations.show', [$workstation]) }}?tab=applications">
                @svg('heroicon-s-reply', 'h-5 w-5 text-gray-600')
            </a>
        </div>

        <div class="mt-6 sm:mt-2 2xl:mt-5">
            <div class="border-b border-gray-200">
                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <a
                            href="#"
                            class="text-gray-900 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                            aria-current="page"
                        >
                            &nbsp;
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <div class="mt-6 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-2">
                <form action="{{ route('workstations.versions.store', [$workstation]) }}" method="post">
                    @csrf

                    <div class="space-y-1">
                        <x-jet-label for="application_id">Select installed version</x-jet-label>

                        <p id="application_id_helper" class="sr-only">Select installed version</p>

                        <div class="flex">
                            <div class="flex-grow">
                                <select
                                    name="version_id"
                                    id="version_id"
                                    aria-describedby="version_id_helper"
                                    placeholder="Select installed version"
                                    class="w-full block sm:text-sm border-gray-300 focus:ring-pink-500 focus:border-pink-500 rounded-md shadow-sm"
                                >
                                    @foreach($application->versions as $version)
                                        @continue($installedVersions->contains($version))

                                        <option
                                            value="{{ $version->id }}"
                                            {{ $version->id === old('version_id') ? 'checked' : '' }}
                                        >{{ $version->name }}</option>
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

            @if($installedVersions->count())
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
                                @foreach($installedVersions as $version)
                                    <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $version->name }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <form action="{{ route('workstations.versions.destroy', [$workstation, $version]) }}" method="post" class="inline-flex">
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
        </div>
    </article>
</x-app-layout>
