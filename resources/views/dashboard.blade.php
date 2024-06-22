<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire(\App\Livewire\StatsOverview::class)
            <div class="grid grid-cols-2 mt-4 gap-4" >
                <div>
                    <livewire:dashboard-basic-chart />
                </div>
                <div>
                    <livewire:dashboard-advanced-chart />

                </div>

            </div>
        </div>
    </div>
</x-app-layout>
