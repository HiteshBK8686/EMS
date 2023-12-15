<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        $roleAdmin = Role::updateOrCreate(['name' => 'Admin']);
        $permissions = Permission::pluck('id', 'id')->all();
        $roleAdmin->syncPermissions($permissions);

        // HR
        $hrAdmin = Role::updateOrCreate(['name' => 'HR']);
        $permissions = Permission::pluck('id', 'id')->all();
        $hrAdmin->syncPermissions($permissions);

        // User
        $role = Role::updateOrCreate(['name' => 'User']);
        if ($role) {
            $permis = [
                'user-list',
                'public-leave-list',
                'page-list',
                'leave-balance-list',
            ];
            $permissionArray = [];
            foreach ($permis as $permission) {
                $id = Permission::where('name', $permission)->first()->id;
                $permissionArray[$id] = $id;
            }
            $role->syncPermissions($permissionArray);
        }
    }
}
