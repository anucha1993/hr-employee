<?php

// app/Livewire/Roles/PermissionAssignment.php

namespace App\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionAssignment extends Component
{
    public $roleId;
    public $groupedPermissions = [];
    public $selectedPermissions = [];

    protected $listeners = ['set-permission-role' => 'loadRolePermissions'];

    public function loadRolePermissions($roleId)
    {
        $this->roleId = $roleId;
        $this->resetValidation();
        $role = Role::findOrFail($roleId);
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();

        $grouped = Permission::all()->groupBy(function ($item) {
            return explode(' ', $item->name)[1] ?? 'ทั่วไป';
        });
        $this->groupedPermissions = [];
        foreach ($grouped as $key => $group) {
            $this->groupedPermissions[$key] = $group->toArray();
        }

        $this->dispatch('open-modal', 'modal-permission-assignment');
    }

    public function save()
    {
        $role = Role::findOrFail($this->roleId);
        // ป้องกัน error: syncPermissions ต้องรับ array ของชื่อ หรือ id เท่านั้น
        $role->syncPermissions(array_map('strval', $this->selectedPermissions));

        session()->flash('message', 'อัปเดตสิทธิ์เรียบร้อยแล้ว');
        $this->dispatch('close-modal', 'modal-permission-assignment');
    }

    public function render()
    {
        return view('livewire.roles.permission-assignment')->layout('layouts.vertical-main', ['title' => 'จัดการสิทธิ์']);
    }
}
