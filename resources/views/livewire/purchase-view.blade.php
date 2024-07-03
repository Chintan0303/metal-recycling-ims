<div>
    <x-slot name="header">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li>
                    <div class="flex items-center">
                        <a href="{{ route('purchases') }}" wire:navigate class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">
                            {{ __('Purchases') }}
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">{{ 'Product Purchase #'.$this->purchase->id .' ('.$this->purchase->vendor->name.')' }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </x-slot>
    <div class="py-4">
        <div class="mx-auto sm:px-6 lg:px-8 ">
            {{ $this->infolist }}
        </div>
    </div>
    <div class="grid grid-cols-2 py-4" >
        <div>
            <livewire:purchase-basic-break-down-overview :purchase="$purchase" >
        </div>
        <div>
            <livewire:purchase-adv-break-down-overview :purchase="$purchase" >
        </div>
    </div>
    <x-filament-actions::modals />
</div>
