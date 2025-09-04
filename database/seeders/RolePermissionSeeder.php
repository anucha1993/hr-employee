<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // ✅ ล้างข้อมูลเก่าอย่างปลอดภัย
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('role_has_permissions')->truncate();
        Role::query()->delete();
        Permission::query()->delete();

        // ✅ สร้าง Roles
        $superAdmin = Role::firstOrCreate(['name' => 'SuperAdmin']);
        // $admin = Role::firstOrCreate(['name' => 'Admin']);
        // $user = Role::firstOrCreate(['name' => 'User']);
        Role::firstOrCreate(['name' => 'docs']);
        Role::firstOrCreate(['name' => 'sales']);
        Role::firstOrCreate(['name' => 'staff']);

        // ✅ สร้าง Permissions
        $permissions = [
            // User Permissions
            'view user', 'create user', 'edit user', 'delete user',
            // profile
            'view profile', 'create profile', 'edit profile', 'delete profile',
            //customer 
            'view customer', 'create customer', 'edit customer', 'delete customer',
            // Document Permissions
            'view docs', 'create docs', 'edit docs', 'delete docs',
            // Report Permissions
            'view report', 'export report',
            // Global Permissions
             'view global', 'create global', 'edit global', 'delete global',
            // Employee Permissions
            'view employee', 'create employee', 'edit employee', 'delete employee',
        ];


        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // ✅ ดึง permissions ทั้งหมดเป็น collection
        $allPermissions = Permission::all();

        // ✅ Assign permissions to roles
        // $admin->syncPermissions($permissions);
        // $user->syncPermissions(['view user', 'view quote', 'view report']);
        // ป้องกัน error 100%: syncPermissions ต้องรับ array ของชื่อ หรือ id เท่านั้น
        $superAdmin->syncPermissions(Permission::pluck('name')->toArray());

        // ✅ Assign SuperAdmin ให้ผู้ใช้ ID 1, 2
        foreach ([1, 2] as $id) {
            $user = User::find($id);
            if ($user) {
                $user->syncRoles([$superAdmin->name]); // ใช้ name (string) เท่านั้น
            }
        }

        // ✅ Assign Admin ให้ผู้ใช้ ID 3
        // $adminUser = User::find(3);
        // if ($adminUser) {
        //     $adminUser->syncRoles([$admin->name]); // ใช้ name (string) เท่านั้น
        // }
    }
}
