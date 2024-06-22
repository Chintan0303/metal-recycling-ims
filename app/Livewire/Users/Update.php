<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class Update extends Component
{
    public $user_id ; 
    public $name ; 
    public $email ; 
    public $role ;

    #[On('show-user-edit-modal')]
    public function showEditModal($id)
    {
        $this->user_id = $id;    
        $this->dispatch('open-modal', 'edit-user');

    }

    public function updateUser()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($this->user_id),
            ],
            'role' => ['required'],
        ]);
        $user = User::find($this->user_id);
        $user->update($validated);
        $this->dispatch('user-updated');
    }

    public function render()
    {
        $user = User::find($this->user_id);
        if ($user) {
            $this->name = $user->name; 
            $this->email = $user->email; 
            $this->role = $user->role;
        }
        return view('livewire.users.update');
    }
}
