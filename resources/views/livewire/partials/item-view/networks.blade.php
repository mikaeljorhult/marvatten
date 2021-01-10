<x-item-view.section key="networks">
    <div
        class="space-y-2"
        x-data
        x-init="
            new Cleave($refs.macaddress, {
                delimiter: ':',
                blocks: [2, 2, 2, 2, 2, 2],
                uppercase: true,
            });
        "
    >
        <form action="{{ route('network-adapters.store') }}" method="post">
            @csrf

            <input type="hidden" name="workstation_id" value="{{ $this->item->id }}" />

            <div class="space-y-2">
                <div>
                    <x-jet-label for="name">Name</x-jet-label>

                    <p id="name_helper" class="sr-only">Name of the network adapter</p>

                    <x-jet-input
                        type="text"
                        name="name"
                        id="name"
                        aria-describedby="name_helper"
                        placeholder="Ethernet XX"
                        value="{{ old('name') }}"
                        class="w-full"
                    />
                </div>

                <div>
                    <x-jet-label for="mac_address">MAC Address</x-jet-label>

                    <p id="macaddress_helper" class="sr-only">MAC address of the network adapter</p>

                    <div class="flex">
                        <x-jet-input
                            type="text"
                            name="mac_address"
                            id="mac_address"
                            aria-describedby="macaddress_helper"
                            placeholder="00:00:00:00:00:00"
                            value="{{ old('mac_address') }}"
                            class="w-full"
                            x-ref="macaddress"
                        />

                        <div class="flex justify-end ml-2">
                            <x-jet-button>
                                <x-heroicon-s-plus class="w-5 h-5 -ml-2 mr-1" />
                                <span>Add</span>
                            </x-jet-button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @if($this->item->networkAdapters->count())
        <div class="flex flex-col py-4">
            <div class="overflow-hidden border-b border-gray-200 shadow-sm sm:rounded-lg">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>

                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                MAC Address
                            </th>

                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($this->item->networkAdapters as $adapter)
                            <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $adapter->name }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $adapter->mac_address }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <x-jet-button
                                        type="link"
                                        href="{{ route('network-adapters.edit', [$adapter]) }}"
                                    >
                                        @svg('heroicon-s-pencil', 'h-2 w-2')
                                    </x-jet-button>

                                    <form action="{{ route('network-adapters.destroy', [$adapter]) }}" method="post" class="inline-flex">
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
