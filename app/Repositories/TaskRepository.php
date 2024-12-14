<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository implements TaskRepositoryInterface
{
    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function all()
    {
        return $this->task->with(['employee', 'manager'])->get();
    }

    public function find($id)
    {
        return $this->task->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->task->create($data);
    }

    public function update($id, array $data)
    {
        $task = $this->find($id);
        $task->update($data);
        return $task;
    }

    public function delete($id)
    {
        $task = $this->find($id);
        return $task->delete();
    }

    public function getTasksByEmployeeId($employeeId)
    {
        return $this->task->where('employee_id', $employeeId)->with(['employee', 'manager'])->get();
    }
}
