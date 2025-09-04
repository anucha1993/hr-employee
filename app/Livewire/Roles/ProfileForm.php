<?php

// app/Livewire/Roles/ProfileForm.php

namespace App\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class ProfileForm extends Component
{
    public $role_id;
    public $name;

    protected $listeners = ['edit-profile' => 'loadRole'];

    public function loadRole($id)
    {
        $this->resetValidation();
        $this->role_id = $id;

        if ($id) {
            $role = Role::findOrFail($id);
            $this->name = $role->name;
        } else {
            $this->reset(['name']);
        }

        $this->dispatch('open-modal', 'modal-profile-form');
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:roles,name,' . $this->role_id,
        ]);

        Role::updateOrCreate(['id' => $this->role_id], ['name' => $this->name]);

        session()->flash('message', 'บันทึกโปรไฟล์สำเร็จ');
        $this->dispatch('close-modal', 'modal-profile-form');
        $this->dispatch('refresh'); // ให้ ProfileIndex รีโหลด
    }

    public function render()
    {
        return view('livewire.roles.profile-form')->layout('layouts.vertical-main', ['title' => 'รายชื่อโปรไฟล์']);
    }
}
