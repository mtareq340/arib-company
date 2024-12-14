<?php

namespace App\Repositories;

interface DepartmentRepositoryInterface
{
    public function getAllDepartmentsWithEmployees();
    public function createDepartment(array $data);
    public function findDepartmentById($id);
    public function updateDepartment($id, array $data);
    public function deleteDepartment($id);
}
