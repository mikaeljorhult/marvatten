<x-app-layout>
    <x-slot name="sidebar">
        <livewire:item-list :model="\App\Models\User::class" />
    </x-slot>

    <article class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900 truncate">
                Create user
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

        <form action="{{ route('users.store') }}" method="post">
            @csrf

            <div class="mt-6 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <x-jet-label for="name">Name</x-jet-label>

                        <div class="mt-1">
                            <x-jet-input
                                type="text"
                                name="name"
                                id="name"
                                autocomplete="name"
                                value="{{ old('name') }}"
                                class="w-full"
                            />

                            <x-jet-input-error for="name" class="mt-2" />
                        </div>
                    </div>

                    <div class="sm:col-span-4">
                        <x-jet-label for="email">E-mail</x-jet-label>

                        <div class="mt-1">
                            <x-jet-input
                                type="email"
                                name="email"
                                id="email"
                                autocomplete="email"
                                value="{{ old('email') }}"
                                class="w-full"
                            />

                            <x-jet-input-error for="email" class="mt-2" />
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <x-jet-label for="password">Password</x-jet-label>

                        <div class="mt-1">
                            <x-jet-input
                                type="password"
                                name="password"
                                id="password"
                                autocomplete="new-password"
                                class="w-full"
                            />

                            <x-jet-input-error for="password" class="mt-2" />
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <x-jet-label for="password_confirmation">Confirm password</x-jet-label>

                        <div class="mt-1">
                            <x-jet-input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                autocomplete="new-password"
                                class="w-full"
                            />

                            <x-jet-input-error for="password_confirmation" class="mt-2" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-end">
                    <x-jet-secondary-button
                        type="link"
                        href="{{ route('users.index') }}"
                        class="mr-2"
                    >Cancel</x-jet-secondary-button>

                    <x-jet-button>Create</x-jet-button>
                </div>
            </div>
        </form>
    </article>
</x-app-layout>
