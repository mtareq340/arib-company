<?php

namespace App\Http\Controllers;

use App\Repositories\EmployeeRepositoryInterface;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\Department;

class EmployeeController extends Controller
{
    protected $employeeRepository;

    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function index()
    {
        $users = $this->employeeRepository->getAllEmployees();
        $roles = Role::all();
        $departments = Department::all();
        $managers = $this->employeeRepository->getManagers();

        $heads = [
            'ID',
            'Name',
            'Email',
            'Phone',
            'Salary',
            'Role',
            'Department',
            'Manager',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        return view('dashboard.employees.index', compact('heads', 'users', 'roles', 'departments', 'managers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
            'salary' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'required|exists:departments,id',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $this->employeeRepository->storeEmployee($validated);
        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:users,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'salary' => 'required|numeric|min:0',
            'role_id' => 'nullable|exists:roles,id',
            'department_id' => 'nullable|exists:departments,id',
            'manager_id' => 'nullable|exists:users,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $this->employeeRepository->updateEmployee($validated['id'], $validated);
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy($id)
    {
        $this->employeeRepository->deleteEmployee($id);
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
