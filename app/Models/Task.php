<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'manager_id',
        'title',
        'description',
        'status',
    ];

    // Relationship: Task belongs to an employee
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    // Relationship: Task belongs to a manager
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}