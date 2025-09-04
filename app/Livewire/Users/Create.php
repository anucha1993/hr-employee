<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class Create extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $selectedRoles = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'selectedRoles' => 'required|array|min:1'
    ];

    protected $messages = [
        'name.required' => 'กรุณากรอกชื่อ-นามสกุล',
        'email.required' => 'กรุณากรอกอีเมล',
        'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
        'email.unique' => 'อีเมลนี้ถูกใช้งานแล้ว',
        'password.required' => 'กรุณากรอกรหัสผ่าน',
        'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร',
        'password.confirmed' => 'ยืนยันรหัสผ่านไม่ตรงกัน',
        'selectedRoles.required' => 'กรุณาเลือกบทบาท',
        'selectedRoles.min' => 'กรุณาเลือกอย่างน้อย 1 บทบาท'
    ];

    public function create()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $user->assignRole($this->selectedRoles);

        session()->flash('success', 'เพิ่มผู้ใช้งานเรียบร้อยแล้ว');
        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.users.create', [
            'roles' => Role::all()
        ]);
    }
}
