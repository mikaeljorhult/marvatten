<x-app-layout>
    <div class="grid grid-cols-3 gap-4 p-6">
        <div class="col-span-3">
            <h1 class="uppercase text-2xl">Marvatten</h1>
        </div>

        <div class="col-span-1">
            &nbsp;
        </div>

        <div class="col-span-1">
            <div class="p-4 pb-8 text-sm bg-gray-50 rounded">
                @if($attentionWorkstations->count())
                    <div>
                        <h2 class="inline uppercase font-medium">Workstations</h2>:
                        <span class="text-red-700">Needs attention</span>
                    </div>

                    @if($attentionWorkstations->count() === 1)
                        <a href="{{ route('workstations.show', [$attentionWorkstations->first()]) }}">One
                    @else
                        <a href="{{ route('workstations.index') }}">Multiple
                    @endif

                        {{ Str::plural('workstation', $attentionWorkstations->count()) }}</a> needs attention.
                    @else
                        <div>
                            <h2 class="inline uppercase font-medium">Workstations</h2>: Up to date
                        </div>
                        All workstations are up to date.
                    @endif
            </div>
        </div>

        <div class="col-span-1">
            <div class="p-4 pb-8 text-sm bg-gray-50 rounded">
                @if($attentionApplications->count())
                    <div>
                        <h2 class="inline uppercase font-medium">Applications</h2>:
                        <span class="text-red-700">Needs attention</span>
                    </div>

                    @if($attentionApplications->count() === 1)
                        <a href="{{ route('applications.show', [$attentionApplications->first()]) }}">One
                    @else
                        <a href="{{ route('applications.index') }}">Multiple
                    @endif

                    {{ Str::plural('applications', $attentionApplications->count()) }}</a> needs attention.
                @else
                    <div>
                        <h2 class="inline uppercase font-medium">Applications</h2>: Up to date
                    </div>
                    All applications are up to date.
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
