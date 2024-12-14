<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DepartmentController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:manager'])->prefix('admin')->group(function () {
  
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::patch('/employees/update', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

    // Task Routes
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index'); 
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store'); 
    Route::patch('/tasks/update', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy'); 

    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::patch('/departments/update', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

});
Route::middleware(['auth', 'role:employee'])->prefix('employee')->group(function () {
    Route::get('/tasks', [TaskController::class, 'employeeTasks'])->name('employee_tasks.index'); 
    Route::patch('tasks/update-status', [TaskController::class, 'updateTaskStatus'])->name('tasks.updateStatus');
});



require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
