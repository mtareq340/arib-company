<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\DepartmentRepositoryInterface;

class DepartmentController extends Controller
{
    protected $departmentRepository;

    public function __construct(DepartmentRepositoryInterface $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    /**
     * Display a listing of departments.
     */
    public function index()
    {
        $departments = $this->departmentRepository->getAllDepartmentsWithEmployees();

        $heads = ['ID', 'Name', 'Employee Count', 'Total Salary', 'Actions'];

        return view('dashboard.departments.index', compact('departments', 'heads'));
    }

    /**
     * Store a newly created department.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $this->departmentRepository->createDepartment($request->all());

        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    /**
     * Show the form for editing a department.
     */
    public function edit($id)
    {
        $department = $this->departmentRepository->findDepartmentById($id);

        return view('dashboard.departments.edit', compact('department'));
    }

    /**
     * Update the specified department.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $this->departmentRepository->updateDepartment($validatedData['id'], $validatedData);

        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified department.
     */
    public function destroy($id)
    {
        try {
            $this->departmentRepository->deleteDepartment($id);
            return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('departments.index')->with('error', $e->getMessage());
        }
    }
}
