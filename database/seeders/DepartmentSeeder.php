<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        // Add your department data here
        Department::insert([
            ['name' => 'Human Resources', 'description' => 'Handles recruitment and employee welfare.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Finance', 'description' => 'Manages financial operations and payroll.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'IT', 'description' => 'Handles all IT-related tasks and infrastructure.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Marketing', 'description' => 'Focuses on marketing and branding activities.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sales', 'description' => 'Responsible for sales and client management.', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
