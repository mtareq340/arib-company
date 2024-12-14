<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EmployeeLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.employee_login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('employee')->attempt($credentials)) {
            return redirect()->route('employee.dashboard');
        }

        return redirect()->back()->with('error', 'Invalid credentials.');
    }
}

