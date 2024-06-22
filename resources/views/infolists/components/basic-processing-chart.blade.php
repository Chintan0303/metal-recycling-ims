<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div>
        {{-- {{ $getState() }} --}}
        {{-- @livewire(BasicProcessingChart::class ,  ['record' => $getState()]) --}}
        <livewire:basic-processing-chart  :id="$getState()"/>
    </div>
</x-dynamic-component>
