<div>
    <form wire:submit="create" class=" border rounded-lg p-4 space-y-4">
        <x-success-notification on="user-created">
            {{ __('Created Successfully') }}
        </x-success-notification >
        <h1 class="text-sm font-medium">{{ __('Create new User') }}</h1>
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" autocomplete="off" placeholder="Name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" name="email" type="text" class="mt-1 block w-full" autocomplete="off" placeholder="Email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input wire:model="password" id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="off" placeholder="Password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="off" placeholder="Confirm Password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="role" :value="__('Role')" />
            <x-select id="role" wire:model="role" name="role">
                <option value="0">Employee</option>
                <option value="1">Admin</option>
            </x-select>
        </div>
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Create') }}</x-primary-button>
        </div>
    </form>
</div>
