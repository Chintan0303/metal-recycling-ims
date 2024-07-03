<div>
    <x-slot name="header">
        <div class="flex justify-between" >
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li>
                        <div class="flex items-center">
                            <a href="{{ route('advanced-processings') }}" wire:navigate class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">
                                {{ __('Aluminium Processings') }}
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">
                                #{{ $advProcessing->id }}
                            </span>
                            </div>
                    </li>
                </ol>
            </nav>
            <div>
                <a class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" href="{{  URL::previous()  }}">Back</a>
            </div>
        </div>
    </x-slot>
    <div class="py-4">
        <div class="  mx-auto sm:px-6 lg:px-8">
            {{ $this->productionInfolist }}
        </div>
    </div>
    <x-filament-actions::modals />
</div>
