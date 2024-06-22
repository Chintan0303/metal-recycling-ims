<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>
    <div class="py-8">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="bg-white grid lg:grid-cols-2 dark:bg-gray-800  shadow-sm  sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <livewire:users.add />
                </div>
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <livewire:users.index />
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>




