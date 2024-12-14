<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade'); // Task assigned to employee
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null'); // Task assigned by manager
            $table->string('title'); 
            $table->text('description'); 
            $table->enum('status', ['pending', 'completed'])->default('pending'); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
