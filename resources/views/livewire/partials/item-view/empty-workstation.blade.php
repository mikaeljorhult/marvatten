<article class="flex items-center h-full pt-6 pb-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        @svg('heroicon-s-desktop-computer', 'w-32 h-32 mx-auto text-gray-200')

        <div class="mt-6 text-gray-700">
            Select a workstation from the list or create a new.
        </div>

        <x-jet-button
            type="link"
            href="{{ route('workstations.create') }}"
            class="mt-2"
        >Add workstation</x-jet-button>
    </div>
</article>
