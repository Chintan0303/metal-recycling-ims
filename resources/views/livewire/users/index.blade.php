<div class="overflow-hidden rounded-lg bg-white border w-full space-y-4">
    <h1 class="text-sm font-medium px-4 pt-4">{{ __('All Users') }}</h1>
    <table class="w-full">
        <thead >
            <tr>
                <th class="p-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                    {{ __('Name') }}
                </th>
                <th class="p-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                    {{ __('Email') }}
                </th>
                <th class="p-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                    {{ __('Role') }}
                </th>
                <th class="p-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                </th>
                <th class="p-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                </th>
            </tr>
        </thead>
        <tbody>
            <tbody>
                @forelse ($users as  $user )
                    <tr  
                        class="{{ $loop->odd ? 'bg-gray-100' : 'bg-gray-50' }} border-b "
                        wire:key='{{ $user->id }}'
                    >
                        <td class="p-2 whitespace-nowrap text-left">
                            {{ $user->name }}
                        </td>
                        <td class="p-2 whitespace-nowrap text-left">
                            {{ $user->email }}
                        </td>
                        <td class="p-2 whitespace-nowrap text-left">
                            {{ $user->role == 1 ? 'Admin' : 'Employee' }}
                        </td>
                        <td class="p-2 whitespace-nowrap text-left">
                            <x-heroicon-m-pencil-square class="w-6 h-6 text-gray-500 cursor-pointer" 
                                wire:click.prevent="$dispatch('show-user-edit-modal',{id:{{ $user->id }}})"         
                            />
                        </td>
                        <td class="p-2 whitespace-nowrap text-left">
                            <x-heroicon-m-archive-box-x-mark class="w-6 h-6 text-red-600 cursor-pointer"
                                wire:click.prevent="$dispatch('show-user-delete-modal',{id:{{ $user->id }}})"         
                            />
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white border-b justify-between">
                        <td colspan="5" class="p-2 whitespace-nowrap" >No records found</td>
                    </tr>
                @endforelse 
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">
                    {{ $users->links(data: ['scrollTo' => false]) }}
                </td>
            </tr>
        </tfoot>
    </table> 
    <x-modal name="edit-user" >
        <livewire:users.update />
    </x-modal>
    <x-modal name="delete-user" >
        <livewire:users.delete />
    </x-modal>
                   
</div>