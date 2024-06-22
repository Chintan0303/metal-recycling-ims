<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div>
        <livewire:advanced-processing-chart  :id="$getState()"/>
    </div>
</x-dynamic-component>
