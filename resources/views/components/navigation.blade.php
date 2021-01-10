<div
    class="lg:hidden"
    x-data="{ isOpen: false }"
    x-show="isOpen == true"
    x-on:open-sidebar.window="isOpen = true"
    x-cloak
>
    <div class="fixed inset-0 flex z-40">
        <div
            class="fixed inset-0"
            x-show="isOpen"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div class="absolute inset-0 bg-gray-600 opacity-75"></div>
        </div>

        <div
            tabindex="0" class="relative flex-1 flex flex-col max-w-xs w-full bg-white focus:outline-none transition ease-in-out duration-300 transform"
            x-show="isOpen"
            x-transition:enter="transition ease-in-out duration-300 transform"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in-out duration-300 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
        >
            <div class="absolute top-0 right-0 -mr-12 pt-2">
                <button
                    type="button"
                    class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                    x-on:click.prevent="isOpen = false"
                >
                    <span class="sr-only">Close sidebar</span>
                    <x-heroicon-o-x class="w-6 h-6 text-white" />
                </button>
            </div>

            <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                <div class="flex-shrink-0 flex items-center px-4">
                    <x-jet-application-mark class="block h-9 w-auto" />
                </div>

                <nav aria-label="Sidebar" class="mt-5">
                    <div class="px-2 space-y-1">
                        <x-jet-responsive-nav-link href="{{ route('dashboard') }}" :icon="'home'" :active="request()->routeIs('dashboard')">
                            Dashboard
                        </x-jet-responsive-nav-link>

                        <x-jet-responsive-nav-link href="{{ route('workstations.index') }}" :icon="'desktop-computer'" :active="request()->routeIs('workstations.index')">
                            Workstations
                        </x-jet-responsive-nav-link>

                        <x-jet-responsive-nav-link href="{{ route('applications.index') }}" :icon="'collection'" :active="request()->routeIs('applications.index')">
                            Applications
                        </x-jet-responsive-nav-link>

                        <x-jet-responsive-nav-link href="{{ route('users.index') }}" :icon="'user-group'" :active="request()->routeIs('users.index')">
                            Users
                        </x-jet-responsive-nav-link>
                    </div>
                </nav>
            </div>

            <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                <a href="{{ route('profile.show') }}" class="flex-shrink-0 group block">
                    <div class="flex items-center">
                        <div>
                            <img class="inline-block h-10 w-10 rounded-full" src="{{ auth()->user()->getProfilePhotoUrlAttribute() }}" alt="Profile Photo">
                        </div>

                        <div class="ml-3">
                            <p class="text-base font-medium text-gray-700 group-hover:text-gray-900">
                                {{ auth()->user()->name }}
                            </p>

                            <p class="text-sm font-medium text-gray-500 group-hover:text-gray-700">
                                View profile
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="flex-shrink-0 w-14" aria-hidden="true">
            <!-- Force sidebar to shrink to fit close icon -->
        </div>
    </div>
</div>

<!-- Static sidebar for desktop -->
<div class="hidden lg:flex lg:flex-shrink-0">
    <div class="flex flex-col w-64">
        <div class="flex flex-col h-0 flex-1 border-r border-gray-200 bg-gray-100">
            <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                <div class="flex items-center flex-shrink-0 px-4">
                    <x-jet-application-mark class="block h-9 w-auto mr-2" />
                </div>

                <nav class="mt-5 flex-1" aria-label="Sidebar">
                    <div class="px-2 space-y-1">
                        <x-jet-nav-link href="{{ route('dashboard') }}" :icon="'home'" :active="request()->routeIs('dashboard')">
                            Dashboard
                        </x-jet-nav-link>

                        <x-jet-nav-link href="{{ route('workstations.index') }}" :icon="'desktop-computer'" :active="request()->routeIs('workstations.index')">
                            Workstations
                        </x-jet-nav-link>

                        <x-jet-nav-link href="{{ route('applications.index') }}" :icon="'collection'" :active="request()->routeIs('applications.index')">
                            Applications
                        </x-jet-nav-link>

                        <x-jet-nav-link href="{{ route('users.index') }}" :icon="'user-group'" :active="request()->routeIs('users.index')">
                            Users
                        </x-jet-nav-link>
                    </div>
                </nav>
            </div>

            <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                <a href="{{ route('profile.show') }}" class="flex-shrink-0 w-full group block">
                    <div class="flex items-center">
                        <div>
                            <img class="inline-block h-9 w-9 rounded-full" src="{{ auth()->user()->getProfilePhotoUrlAttribute() }}" alt="Profile Photo">
                        </div>

                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-700 group-hover:text-gray-900">
                                {{ auth()->user()->name }}
                            </p>

                            <p class="text-xs font-medium text-gray-500 group-hover:text-gray-700">
                                View profile
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
