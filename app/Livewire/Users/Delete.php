<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class Delete extends Component
{
    public $user_id ; 
    public $user_name ; 
    public $password = '';


    #[On('show-user-delete-modal')]
    public function showDeleteModal($id)
    {
        $this->reset();
        $this->resetValidation();
        $this->user_id = $id;
        $this->user_name = User::find($id)->name;    
        $this->dispatch('open-modal', 'delete-user');
    }

    public function deleteUserByAdmin()
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);    
        User::destroy($this->user_id);
        $this->reset();
        $this->dispatch('close-modal', 'delete-user');        
        $this->dispatch('user-deleted');        
    }

    public function render()
    {
        return view('livewire.users.delete');
    }
}
