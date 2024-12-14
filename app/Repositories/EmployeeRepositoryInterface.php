<?php

namespace App\Repositories;

use App\Models\User;

interface EmployeeRepositoryInterface
{
    public function getAllEmployees();
    public function storeEmployee(array $data);
    public function updateEmployee(int $id, array $data);
    public function deleteEmployee(int $id);
    public function getManagers();
}
