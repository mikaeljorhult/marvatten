<x-item-view.layout>
    <div class="mt-6 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
            @can('delete', $this->item)
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">
                        Manage
                    </dt>

                    <dd class="mt-1 text-sm text-gray-900">
                        <form action="{{ route(\Str::plural($this->model).'.destroy', [$this->item]) }}" method="POST">
                            @method('DELETE')
                            @csrf

                            <x-jet-button>Delete</x-jet-button>
                        </form>
                    </dd>
                </div>
            @endcan
        </dl>
    </div>
</x-item-view.layout>
