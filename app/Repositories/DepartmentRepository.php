<?php

namespace App\Repositories;

use App\Models\Department;

class DepartmentRepository implements DepartmentRepositoryInterface
{
    public function getAllDepartmentsWithEmployees()
    {
        return Department::withCount('employees')->withSum('employees', 'salary')->get();
    }

    public function createDepartment(array $data)
    {
        return Department::create($data);
    }

    public function findDepartmentById($id)
    {
        return Department::findOrFail($id);
    }

    public function updateDepartment($id, array $data)
    {
        $department = Department::findOrFail($id);
        $department->update($data);
        return $department;
    }

    public function deleteDepartment($id)
    {
        $department = Department::findOrFail($id);
        if ($department->employees()->exists()) {
            throw new \Exception('Cannot delete department with assigned employees.');
        }
        return $department->delete();
    }
}
