<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'broadcast-notification',

            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'user-profile-review',

            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            'public-leave-list',
            'public-leave-create',
            'public-leave-edit',
            'public-leave-delete',

            'page-list',
            'page-private-list',
            'page-create',
            'page-edit',
            'page-delete',

            'leave-applications-status-edit',

            'leave-balance-list',
            'leave-balance-create',
            'leave-balance-edit',
            'leave-balance-delete',

            'activity-log-list',
            'activity-log-create',
            'activity-log-edit',
            'activity-log-delete',

            'file-all-list',
        ];
        foreach ($permissions as $permission) {
            if (! Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
            }
        }
    }
}
