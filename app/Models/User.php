<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory;
    use HasRoles;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'salary',
        'image',
        'department_id',
        'manager_id',
    ];

    protected $hidden = ['password', 'remember_token'];
    

    // Relationship: User belongs to a department
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    // Relationship: User belongs to a manager (self-referencing)
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    // Relationship: User has many employees under them (self-referencing)
    public function employees(): HasMany
    {
        return $this->hasMany(User::class, 'manager_id');
    }

    // Relationship: User can have many tasks assigned to them
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'employee_id');
    }
}