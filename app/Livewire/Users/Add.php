<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Illuminate\Validation\Rules;

class Add extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public int $role = 1;

    public function create(){
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'role' => ['required'],
        ]);

        $validated['password'] = bcrypt($validated['password']);
        User::create($validated);
        $this->reset();
        $this->dispatch('user-created');
    }   

    public function render()
    {
        return view('livewire.users.add');
    }
}
