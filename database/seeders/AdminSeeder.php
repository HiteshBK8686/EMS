<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\EmploymentDetail;
use App\Models\User;
use App\Models\UserInformation;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Admin
        $user = User::where('email', 'admin@hauper.com')->first();
        if (! $user) {

            $user = User::create([
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@gmail.com',
                'phone' => '9978707853',
                'password'=> Hash::make('admin@123'),
                'status'=> '1',
            ]);

            $address = Address::updateOrCreate([
                'line1' => '623, Shivalik Shilp',
                'line2' => 'Near Iscon Circle,Sarkhej - Gandhinagar Hwy',
                'city' => 'Ahmedabad',
                'state' => 'Gujarat',
                'zip' => '380015',
            ]);

            $info = UserInformation::updateOrCreate([
                'user_id' => $user->id,
                'personal_email' => 'contact@hauper.com',
                'emergency_phone' => '9978707853',
                'date_of_birth' => date('Y-m-d'),
                'current_address_id' => $address->id,
                'permanent_address_id' => $address->id,
                'skills' => ['All'],
            ]);

            EmploymentDetail::updateOrCreate([
                'user_id' => $user->id,
                'joining_date' => date('Y-m-d'),
                'designation' => 'CEO',
            ]);
        }

        $role = Role::where('name', 'Admin')->first();
        $permissions = $role->permissions;
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
    }
}
