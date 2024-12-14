<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    // Relationship: A department can have many users
    public function employees()
    {
        return $this->hasMany(User::class);
    }
 
}