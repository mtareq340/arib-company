<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ManagerLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.manager_login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('manager')->attempt($credentials)) {
            return redirect()->route('manager.dashboard');
        }

        return redirect()->back()->with('error', 'Invalid credentials.');
    }
}

