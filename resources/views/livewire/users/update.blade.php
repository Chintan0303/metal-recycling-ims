<div>
    <div class="bg-white">
    <form wire:submit="updateUser" class=" border rounded-lg p-4 space-y-4" >
        <x-success-notification on="user-updated">
            {{ __('Updated Successfully') }}
        </x-success-notification >
        <h1 class="text-sm font-medium">{{ __('Edit User') }}</h1>
        <div>
            <x-input-label for="editname" :value="__('Name')" />
            <x-text-input wire:model="name" id="editname" name="name" type="text" class="mt-1 block w-full" autocomplete="off" placeholder="Name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="editemail" :value="__('Email')" />
            <x-text-input wire:model="email" id="editemail" name="email" type="text" class="mt-1 block w-full" autocomplete="off" placeholder="Email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="editrole" :value="__('Role')" />
            <x-select id="editrole" wire:model="role" name="role">
                <option value="0">Employee</option>
                <option value="1">Admin</option>
            </x-select>
        </div>
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Update') }}</x-primary-button>
        </div>
    </form>
</div>  
</div>
