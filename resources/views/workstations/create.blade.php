<x-app-layout>
    <x-slot name="sidebar">
        <livewire:workstation-list :model="\App\Models\Workstation::class" />
    </x-slot>

    <article class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900 truncate">
                Create workstation
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

        <form action="{{ route('workstations.store') }}" method="post">
            @csrf

            <div
                class="mt-6 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8"
                x-data="{ fields: {{ json_encode(old('fields', [])) }} }"
            >
                <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <x-jet-label for="name">Name</x-jet-label>

                        <div class="mt-1">
                            <x-jet-input
                                type="text"
                                name="name"
                                id="name"
                                autocomplete="on"
                                value="{{ old('name') }}"
                                class="w-full"
                            />

                            <x-jet-input-error for="name" class="mt-2" />
                        </div>
                    </div>

                    <div class="sm:col-span-4">
                        <x-jet-label for="serial">Serial Number</x-jet-label>

                        <div class="mt-1">
                            <x-jet-input
                                type="text"
                                name="serial"
                                id="serial"
                                autocomplete="on"
                                value="{{ old('serial') }}"
                                class="w-full"
                            />

                            <x-jet-input-error for="serial" class="mt-2" />
                        </div>
                    </div>
                </div>

                <h2 class="mt-8">
                    Additional Fields
                    <x-jet-button
                        x-on:click.prevent="fields.push({ id: null, label: '', value: '' })"
                    >
                        <x-heroicon-s-plus class="w-3 h-3" />
                        <span class="sr-only">Add</span>
                    </x-jet-button>
                </h2>

                <template x-for="(item, index) in fields" x-bind:key="index">
                    <div class="mt-4 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <x-jet-label x-bind:for="'fields[' + index + '][label]'">Label</x-jet-label>
                            <x-jet-input
                                type="text"
                                x-bind:name="'fields[' + index + '][label]'"
                                x-bind:id="'fields[' + index + '][label]'"
                                x-model="item.label"
                                autocomplete="on"
                                class="w-full"
                            />
                        </div>

                        <div class="sm:col-span-3">
                            <x-jet-label x-bind:for="'fields[' + index + '][value]'">Value</x-jet-label>
                            <x-jet-input
                                type="text"
                                x-bind:name="'fields[' + index + '][value]'"
                                x-bind:id="'fields[' + index + '][value]'"
                                x-model="item.value"
                                autocomplete="on"
                                class="w-full"
                            />
                        </div>

                        <div class="sm:col-span-1 self-end">
                            <x-jet-button
                                type="button"
                                x-on:click.prevent="fields.splice(index, 1)"
                            >
                                <x-heroicon-s-minus class="w-5 h-5" />
                                <span class="sr-only">Remove</span>
                            </x-jet-button>
                        </div>
                    </div>
                </template>
            </div>

            <div class="mt-6 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-end">
                    <x-jet-secondary-button
                        type="link"
                        href="{{ route('workstations.index') }}"
                        class="mr-2"
                    >Cancel</x-jet-secondary-button>

                    <x-jet-button>Create</x-jet-button>
                </div>
            </div>
        </form>
    </article>
</x-app-layout>
