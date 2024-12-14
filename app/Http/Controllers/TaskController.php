<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks.
     */
    public function index()
    {
        $tasks = Task::with(['employee', 'manager'])->get();
        $employees = User::role('Employee')->get();
        $managers = User::role('Manager')->get();

        // Define headers for the DataTable
        $heads = [
            'ID',
            'Title',
            'Description',
            'Status',
            'Employee',
            'Manager',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        return view('dashboard.tasks.index', compact('heads', 'tasks', 'employees', 'managers'));
    }

    public function employeeTasks()
    {
        $user = auth()->user();

        $tasks = Task::with(['employee', 'manager'])
                    ->where('employee_id', $user->id)
                    ->get();

        $heads = [
            'ID',
            'Title',
            'Description',
            'Status',
            'Employee',
            'Manager',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        return view('dashboard.tasks.employee_tasks', compact('heads', 'tasks'));
    }

    public function updateTaskStatus(Request $request)
    {
        $task = Task::findOrFail($request->input('id'));

        $task->status = $request->input('status');
        $task->save();

        return redirect()->route('employee_tasks.index')->with('success', 'Task status updated successfully.');
    }



    /**
     * Store a newly created task.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'employee_id' => 'required|exists:users,id',
            'manager_id' => 'nullable|exists:users,id',
            'status' => 'required|string|max:50',
        ]);

        // Create a new task
        Task::create($request->all());

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    /**
     * Show the form for editing a task.
     */
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        $employees = User::where('role', 'employee')->get();
        $managers = User::where('role', 'manager')->get();

        return view('dashboard.tasks.edit', compact('task', 'employees', 'managers'));
    }

    /**
     * Update the specified task.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:tasks,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Find the task by ID and update it
        $task = Task::findOrFail($validatedData['id']);
        $task->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
        ]);

        return redirect()->back()->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified task.
     */
    public function destroy($id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();

            return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('tasks.index')->with('error', 'Failed to delete task.');
        }
    }
}
