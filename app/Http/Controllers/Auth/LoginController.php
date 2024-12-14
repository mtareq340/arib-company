<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Override the login method to handle login via either email or phone.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Check if the input is an email or phone number
        $loginField = $this->username($request);

        $credentials = [
            $loginField => $request->input('login'),
            'password'  => $request->input('password'),
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Determine if the input is an email or phone number.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    public function username()
    {
        $login = request()->input('login');

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }

        return 'phone';
    }


    /**
     * Validate the login request.
     *
     * @param \Illuminate\Http\Request $request
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'login' => [trans('auth.failed')], // Custom error message
        ]);
    }

}
