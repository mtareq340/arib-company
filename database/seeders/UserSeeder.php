<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee', 'guard_name' => 'web']);

        // Create permissions
        $permissions = [
            'view-employees',
            'view-departments',
            'view-all-tasks',
            'view-own-tasks'
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // Assign permissions to roles
        $managerRole->givePermissionTo(['view-employees', 'view-departments', 'view-all-tasks']);
        $employeeRole->givePermissionTo(['view-own-tasks']);

        // Create manager user
        $manager = User::create([
            'first_name' => 'Admin',
            'last_name' => 'Manager',
            'email' => 'manager@example.com',
            'phone' => '01011305995',
            'salary' => '3000',
            'password' => Hash::make('password'),
            'department_id' => 1,
        ]);

        $manager->assignRole('manager');

        // Create employee user
        $employee = User::create([
            'first_name' => 'John',
            'last_name' => 'Employee',
            'email' => 'employee@example.com',
            'phone' => '01012345678',
            'salary' => '1500',
            'password' => Hash::make('password'),
            'department_id' => 2,
        ]);

        $employee->assignRole('employee');
    }
}
