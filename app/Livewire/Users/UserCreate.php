<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserCreate extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $selectedRole;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'selectedRole' => 'required'
    ];

    public function save()
    {
        $validatedData = $this->validate();
        
        // สร้างผู้ใช้
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password)
        ]);

        // Assign the selected role
        $user->assignRole($this->selectedRole);

        session()->flash('message', 'สร้างผู้ใช้งานเรียบร้อย');
        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.users.user-create', [
            'roles' => Role::all()
        ])->layout('layouts.vertical-main', ['title' => 'เพิ่มผู้ใช้']);;
    }
}
