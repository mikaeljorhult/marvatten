<x-app-layout>
    <x-slot name="sidebar">
        <livewire:application-list :model="\App\Models\Application::class" />
    </x-slot>

    <article class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900 truncate">
                Edit version
            </h1>
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

        <form action="{{ route('applications.versions.update', [$version->application, $version]) }}" method="post">
            @method('put')
            @csrf

            <div class="mt-6 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <x-jet-label for="name">Name</x-jet-label>

                        <div class="mt-1">
                            <x-jet-input
                                type="text"
                                class="w-full"
                                name="name"
                                id="name"
                                autocomplete="on"
                                value="{{ old('name', $version->name) }}"
                            />

                            <x-jet-input-error for="name" class="mt-2" />
                        </div>

                        <div class="py-2">
                            <x-jet-label for="is_current" class="inline-flex items-center">
                                <x-jet-input
                                    type="checkbox"
                                    name="is_current"
                                    id="is_current"
                                    value="1"
                                    :checked="old('is_current', $version->is_current)"
                                />

                                <span class="ml-1">Current version</span>
                            </x-jet-label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-end">
                    <x-jet-secondary-button
                        type="link"
                        href="{{ route('applications.show', [$version->application]) }}"
                        class="mr-2"
                    >Cancel</x-jet-secondary-button>

                    <x-jet-button>Update</x-jet-button>
                </div>
            </div>
        </form>
    </article>
</x-app-layout>
