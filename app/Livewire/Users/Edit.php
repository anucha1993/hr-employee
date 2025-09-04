<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public User $user;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $selectedRoles = [];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'selectedRoles' => 'required|array|min:1'
        ];
    }

    protected $messages = [
        'name.required' => 'กรุณากรอกชื่อ-นามสกุล',
        'email.required' => 'กรุณากรอกอีเมล',
        'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
        'email.unique' => 'อีเมลนี้ถูกใช้งานแล้ว',
        'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร',
        'password.confirmed' => 'ยืนยันรหัสผ่านไม่ตรงกัน',
        'selectedRoles.required' => 'กรุณาเลือกบทบาท',
        'selectedRoles.min' => 'กรุณาเลือกอย่างน้อย 1 บทบาท'
    ];

    public function update()
    {
        $this->validate();

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        if ($this->password) {
            $this->user->update(['password' => Hash::make($this->password)]);
        }

        $this->user->syncRoles($this->selectedRoles);

        session()->flash('success', 'แก้ไขผู้ใช้งานเรียบร้อยแล้ว');
        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.users.edit', [
            'roles' => Role::all()
        ]);
    }
}
