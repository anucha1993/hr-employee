<?php

// app/Livewire/Roles/ProfileIndex.php

namespace App\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ProfileIndex extends Component
{
    public function render()
    {
        return view('livewire.roles.profile-index', [
            'roles' => Role::all(),
            'title' => 'จัดการโปรไฟล์',
        ])->layout('layouts.vertical-main', ['title' => 'จัดการโปรไฟล์']);
    }


public $role_id, $name;
public $groupedPermissions = [];
public $selectedPermissions = [];

public function mount()
{
    $grouped = Permission::all()->groupBy(function ($item) {
        return explode(' ', $item->name)[1] ?? 'ทั่วไป';
    });
    $this->groupedPermissions = [];
    foreach ($grouped as $key => $group) {
        $this->groupedPermissions[$key] = $group->toArray();
    }
}

public function editProfile($id)
{
    $this->role_id = $id;
    $role = Role::findOrFail($id);
    $this->name = $role->name;
}

public function saveProfile()
{
    $this->validate([
        'name' => 'required|unique:roles,name,' . $this->role_id,
    ]);

    Role::updateOrCreate(['id' => $this->role_id], ['name' => $this->name]);

    $this->reset(['role_id', 'name']);
    session()->flash('message', 'บันทึกโปรไฟล์แล้ว');
}

public function openPermissionModal($id)
{
    $this->role_id = $id;
    $role = Role::findOrFail($id);
    $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
}

public function savePermissions()
{
    $role = Role::findOrFail($this->role_id);
    // ป้องกัน error: syncPermissions ต้องรับ array ของชื่อ หรือ id เท่านั้น
    $role->syncPermissions(array_map('strval', $this->selectedPermissions));
    session()->flash('message', 'อัปเดตสิทธิ์แล้ว');
}

}
