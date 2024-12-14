@extends('adminlte::auth.auth-page')

@section('title', 'Login')

@section('auth_header', 'Login to Your Account')

@section('auth_body')
<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="input-group mb-3">
        <input type="text" name="login" class="form-control" placeholder="Email or Phone" required autofocus>
    </div>

    <div class="input-group mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
    </div>

    <div class="row">
        <div class="col-8">
            <div class="icheck-primary">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">
                    Remember Me
                </label>
            </div>
        </div>
        <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </div>
    </div>
</form>

@if ($errors->has('login'))
    <div class="alert alert-danger">
        {{ $errors->first('login') }}
    </div>
@endif
@endsection
