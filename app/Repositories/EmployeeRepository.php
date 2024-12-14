<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    public function getAllEmployees()
    {
        return User::with(['department', 'manager'])->get();
    }

    public function storeEmployee(array $data)
    {
        $data['password'] = Hash::make($data['password']);

        if (isset($data['image']) && $data['image']->isValid()) {
            $data['image'] = $data['image']->store('employees', 'public');
        } else {
            $data['image'] = null;
        }

        $user = User::create($data);

        switch ($data['role_id']) {
            case 1:
                $user->assignRole('manager');
                break;
            case 2:
                $user->assignRole('employee');
                break;
        }

        return $user;
    }

    public function updateEmployee(int $id, array $data)
    {
        $employee = User::findOrFail($id);

        if (isset($data['image']) && $data['image']->isValid()) {
            if ($employee->image && Storage::exists('public/' . $employee->image)) {
                Storage::delete('public/' . $employee->image);
            }

            $data['image'] = $data['image']->store('employees', 'public');
        } else {
            $data['image'] = $employee->image;
        }

        $employee->update($data);
        return $employee;
    }

    public function deleteEmployee(int $id)
    {
        $employee = User::findOrFail($id);
        return $employee->delete();
    }

    public function getManagers()
    {
        return User::role('manager')->get();
    }
}
