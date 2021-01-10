<x-item-view.section key="attachments">
    <div class="space-y-2">
        <form action="{{ route('attachments.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="attachable_type" value="{{ get_class($this->item) }}" />
            <input type="hidden" name="attachable_id" value="{{ $this->item->id }}" />

            <div class="space-y-1">
                <x-jet-label for="name">Name</x-jet-label>

                <p id="name_helper" class="sr-only">Name of the new attachment</p>

                <div class="flex">
                    <div class="flex-grow">
                        <x-jet-input
                            type="text"
                            name="name"
                            id="name"
                            aria-describedby="name_helper"
                            placeholder="Name to be displayed"
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
                    <x-jet-label for="file" class="inline-flex items-center">
                        <input type="file" name="file" id="file">
                        <span class="ml-1 sr-only">Select file to upload</span>
                    </x-jet-label>
                </div>
            </div>
        </form>
    </div>

    @if($this->item->attachments->count())
        <div class="flex flex-col py-4">
            <div class="overflow-hidden border-b border-gray-200 shadow-sm sm:rounded-lg">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>

                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Uploaded By
                            </th>

                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Uploaded On
                            </th>

                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($this->item->attachments as $attachment)
                            <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <a href="{{ route('attachments.show', [$attachment]) }}">
                                        {{ $attachment->name }}
                                    </a>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ optional($attachment->user)->name ?? 'Deleted User' }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $attachment->created_at->format('Y-m-d') }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <x-jet-button
                                        type="link"
                                        href="{{ route('attachments.edit', [$attachment]) }}"
                                    >
                                        @svg('heroicon-s-pencil', 'h-2 w-2')
                                    </x-jet-button>
                                    
                                    <form action="{{ route('attachments.destroy', [$attachment]) }}" method="post" class="inline-flex">
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