<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination , WithoutUrlPagination;

    #[On('user-created')] 
    #[On('user-updated')] 
    #[On('user-deleted')] 
    public function updateUserList() {
        $this->dispatch('$refresh');
    }
    public function render()
    {
        return view('livewire.users.index')->with([
            'users' => User::where('id','!=',auth()->id())->paginate(10),
        ]);
    }
}
