<div class="flex-none relative" >
    <aside 
        class="dark:bg-gray-900  min-h-screen h-full transition-transform ease-in-out duration-300 delay-100 transform translate-x-0"
        :class="{'w-12 bg-slate-200': ! $store.sidebar.sidebarOpen ,
                 'w-40 bg-sidebarColor':$store.sidebar.sidebarOpen ,
                }"
    >
        <template x-if="!$store.sidebar.sidebarOpen">
            <ul class="absolute top-12 w-full">
                <li class="p-3 text-justify text-white font-medium hover:bg-blue-50 hover:text-black rounded" :class="{ 'bg-teal-600' : '{{ request()->routeIs('dashboard')  }}' }">
                    <x-tooltip class="flex relative group">
                        <a  href="{{ route('dashboard') }}"  class="block w-full" wire:navigate>
                            <x-heroicon-c-squares-2x2 class="text-black" />
                        </a>
                        <x-slot name="content">
                            {{ __('Dashboard') }}
                        </x-slot>
                    </x-tooltip>
                </li>
                <li class="p-3 text-justify text-white font-medium hover:bg-blue-50 hover:text-black rounded" :class="{ 'bg-teal-600' : '{{ request()->routeIs('users')  }}' }">
                    <x-tooltip class="flex relative group">
                        <a  href="{{ route('users') }}" class="block w-full" wire:navigate>
                            <x-heroicon-o-user-plus class="text-black"/>
                        </a>
                        <x-slot name="content">
                            {{ __('Users') }}
                        </x-slot>
                    </x-tooltip>
                </li>
                <li class="p-3 text-justify text-white font-medium hover:bg-blue-50 hover:text-black rounded" :class="{ 'bg-teal-600' : '{{ request()->routeIs('vendors*')  }}' }">
                    <x-tooltip class="flex relative group">
                        <a  href="{{ route('vendors') }}"  class="block w-full" wire:navigate>
                            <x-heroicon-o-home-modern class="text-black" />
                        </a>
                        <x-slot name="content">
                            {{ __('Vendors') }}
                        </x-slot>
                    </x-tooltip>
                </li>
                <li class="p-3 text-justify text-white font-medium hover:bg-blue-50 hover:text-black rounded" :class="{ 'bg-teal-600' : '{{ request()->routeIs('customers*')  }}' }">
                    <x-tooltip class="flex relative group">
                        <a  href="{{ route('customers') }}"  class="block w-full" wire:navigate>
                            <x-heroicon-s-building-office-2  class="text-black"/>
                        </a>
                        <x-slot name="content">
                            {{ __('Customers') }}
                        </x-slot>
                    </x-tooltip>
                </li>
                <li class="p-3 text-justify text-white font-medium hover:bg-blue-50 hover:text-black rounded" :class="{ 'bg-teal-600' : '{{ request()->routeIs('materials*')  }}' }">
                    <x-tooltip class="flex relative group">
                        <a  href="{{ route('materials') }}"  class="block w-full" wire:navigate>
                            <x-heroicon-o-wrench class="text-black" />
                        </a>
                        <x-slot name="content">
                            {{ __('Materials') }}
                        </x-slot>
                    </x-tooltip>
                </li>
                <li class="p-3 text-justify text-white font-medium hover:bg-blue-50 hover:text-black rounded" :class="{ 'bg-teal-600' : '{{ request()->routeIs('processed-products*')  }}' }">
                    <x-tooltip class="flex relative group">
                        <a  href="{{ route('processed-products') }}"  class="block w-full" wire:navigate>
                            <x-heroicon-o-cube class="text-black" />
                        </a>
                        <x-slot name="content">
                            {{ __('Products') }}
                        </x-slot>
                    </x-tooltip>
                </li>
                <li class="p-3 text-justify text-white font-medium hover:bg-blue-50 hover:text-black rounded" :class="{ 'bg-teal-600' : '{{ request()->routeIs('scrap-products*')  }}' }">
                    <x-tooltip class="flex relative group">
                        <a  href="{{ route('scrap-products') }}"  class="block w-full" wire:navigate>
                            <x-heroicon-o-tag class="text-black" />
                        </a>
                        <x-slot name="content">
                            {{ __('Scrap') }}
                        </x-slot>
                    </x-tooltip>
                </li>
                <li class="p-3 text-justify text-white font-medium hover:bg-blue-50 hover:text-black rounded" :class="{ 'bg-teal-600' : '{{ request()->routeIs('purchase*')  }}' }">
                    <x-tooltip class="flex relative group">
                        <a  href="{{ route('purchases') }}"  class="block w-full" wire:navigate>
                            <x-heroicon-o-wallet class="text-black" />
                        </a>
                        <x-slot name="content">
                            {{ __('Purchase') }}
                        </x-slot>
                    </x-tooltip>
                </li>
                <li class="p-3 text-justify text-white font-medium hover:bg-blue-50 hover:text-black rounded" :class="{ 'bg-teal-600' : '{{ request()->routeIs('sales*')  }}' }">
                    <x-tooltip class="flex relative group">
                        <a  href="{{ route('sales') }}"  class="block w-full" wire:navigate>
                            <x-heroicon-m-briefcase class="text-black" />
                        </a>
                        <x-slot name="content">
                            {{ __('Sales') }}
                        </x-slot>
                    </x-tooltip>
                </li>
                <li class="p-3 text-justify text-white font-medium hover:bg-blue-50 hover:text-black rounded" :class="{ 'bg-teal-600' : '{{ request()->routeIs('basic-processing*')  }}' }">
                    <x-tooltip class="flex relative group">
                        <a  href="{{ route('basic-processings') }}"  class="block w-full" wire:navigate>
                            <x-heroicon-o-queue-list class="text-black" />
                        </a>
                        <x-slot name="content">
                            {{ __('Basic') }}
                        </x-slot>
                    </x-tooltip>
                </li>
                <li class="p-3 text-justify text-white font-medium hover:bg-blue-50 hover:text-black rounded" :class="{ 'bg-teal-600' : '{{ request()->routeIs('advanced-processing*')  }}' }">
                    <x-tooltip class="flex relative group">
                        <a  href="{{ route('advanced-processings') }}"  class="block w-full" wire:navigate>
                            <x-heroicon-o-cog-6-tooth class="text-black" />
                        </a>
                        <x-slot name="content">
                            {{ __('Advanced') }}
                        </x-slot>
                    </x-tooltip>
                </li>
        </template>
        <template x-if="$store.sidebar.sidebarOpen">
            <ul  class="absolute top-12 w-full">
                <li class="flex p-3 text-justify text-white font-medium hover:bg-blue-50 hover:text-black rounded" :class="{ 'bg-teal-600' : '{{ request()->routeIs('dashboard')  }}' }">
                    <span class="mr-2"><x-heroicon-c-squares-2x2 class=" w-6 h-6"/></span>
                    <a  class="block" href="{{ route('dashboard') }}" wire:navigate>
                        {{ __('Dashboard') }}
                    </a>
                </li>
                <li class="flex p-3 text-justify text-white font-medium hover:bg-blue-50 hover:text-black rounded" :class="{ 'bg-teal-600' : '{{ request()->routeIs('users')  }}' }">
                    <span class="mr-2"><x-heroicon-o-user-plus class=" w-6 h-6" /></span>
                    <a  class="block" href="{{ route('users') }}" wire:navigate>
                        {{ __('Users') }}
                    </a>
                </li>
                <li class="flex p-3 text-justify text-white font-medium hover:bg-blue-50 hover:text-black rounded" :class="{ 'bg-teal-600' : '{{ request()->routeIs('vendors*')  }}' }">
                    <span class="mr-2"><x-heroicon-o-home-modern class=" w-6 h-6"/></span>
                    <a  class="block" href="{{ route('vendors') }}" wire:navigate>
                        {{ __('Vendors') }}
                    </a>
                </li>
                <li class="flex p-3 text-justify text-white font-medium hover:bg-blue-50 hover:text-black rounded" :class="{ 'bg-teal-600' : '{{ request()->routeIs('customers*')  }}' }">
                    <span class="mr-2"><x-heroicon-s-building-office-2 class=" w-6 h-6"/></span>
                    <a  class="block" href="{{ route('customers') }}" wire:navigate>
                        {{ __('Customers') }}
                    </a>
                </li>
                <li class="flex p-3 text-justify text-white font-medium hover:bg-blue-50 hover:text-black rounded" :class="{ 'bg-teal-600' : '{{ request()->routeIs('materials*')  }}' }">
                    <span class="mr-2"><x-heroicon-o-wrench class=" w-6 h-6"/></span>
                    <a  class="block" href="{{ route('materials') }}" wire:navigate>
                        {{ __('Materials') }}
                    </a>
                </li>
                <li class="flex p-3 text-justify text-white font-medium hover:bg-blue-50 hover:text-black rounded" :class="{ 'bg-teal-600' : '{{ request()->routeIs('processed-products*')  }}' }">
                    <span class="mr-2"><x-heroicon-o-cube class=" w-6 h-6"/></span>
                    <a  class="block" href="{{ route('processed-products') }}" wire:navigate>
                        {{ __('Products') }}
                    </a>
                </li>
                <li class="flex p-3 text-justify text-white font-medium hover:bg-blue-50 hover:text-black rounded" :class="{ 'bg-teal-600' : '{{ request()->routeIs('scrap-products*')  }}' }">
                    <span class="mr-2"><x-heroicon-o-tag class=" w-6 h-6"/></span>
                    <a  class="block" href="{{ route('scrap-products') }}" wire:navigate>
                        {{ __('Scrap') }}
                    </a>
                </li>
                <li class="flex p-3 text-justify text-white font-medium hover:bg-blue-50 hover:text-black rounded" :class="{ 'bg-teal-600' : '{{ request()->routeIs('purchase*')  }}' }">
                    <span class="mr-2"><x-heroicon-o-wallet class=" w-6 h-6"/></span>
                    <a  class="block" href="{{ route('purchases') }}" wire:navigate>
                        {{ __('Purchase') }}
                    </a>
                </li>
                <li class="flex p-3 text-justify text-white font-medium hover:bg-blue-50 hover:text-black rounded" :class="{ 'bg-teal-600' : '{{ request()->routeIs('sales*')  }}' }">
                    <span class="mr-2"><x-heroicon-m-briefcase class=" w-6 h-6"/></span>
                    <a  class="block" href="{{ route('sales') }}" wire:navigate>
                        {{ __('Sales') }}
                    </a>
                </li>
                <li class="flex p-3 text-justify text-white font-medium hover:bg-blue-50 hover:text-black rounded" :class="{ 'bg-teal-600' : '{{ request()->routeIs('basic-processing*')  }}' }">
                    <span class="mr-2"><x-heroicon-o-queue-list class=" w-6 h-6"/></span>
                    <a  class="block" href="{{ route('basic-processings') }}" wire:navigate>
                        {{ __('Basic') }}
                    </a>
                </li>
                <li class="flex p-3 text-justify text-white font-medium hover:bg-blue-50 hover:text-black rounded" :class="{ 'bg-teal-600' : '{{ request()->routeIs('advanced-processing*')  }}' }">
                    <span class="mr-2"><x-heroicon-o-cog-6-tooth class=" w-6 h-6"/></span>
                    <a  class="block" href="{{ route('advanced-processings') }}" wire:navigate>
                        {{ __('Advanced') }}
                    </a>
                </li>
            </ul>
        </template>
    </aside>

    <button x-on:click="$store.sidebar.toggle()" class="p-2 absolute top-0 left-1"  :class="{ 'left-28': $store.sidebar.sidebarOpen, 'text-white': $store.sidebar.sidebarOpen}" >
        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
</div>