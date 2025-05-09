@extends('main')

@section('title', 'HR Login')

@section('content')


<style>

    .card-login {
        width: 100%;
        max-width: 540px;
        padding: 4rem;
        margin-left: 360px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        background-color: #fff;
    }

    .form-outline {
        margin-bottom: 1.5rem;
    }

    .btn-primary {
        background-color: #d32f2f;
        border: none;
        width: 100%;
        font-size: 1.1rem;
    }

    .btn-primary:hover {
        background-color: #b71c1c;
    }

    .text-center {
        text-align: center;
    }

    .login-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .login-logo img {
        height: 40px;
        margin-right: 10px;
    }

    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 20px 0;
    }

    .divider::before,
    .divider::after {
        content: "";
        flex: 1;
        border-bottom: 1px solid #ddd;
    }

    .divider:not(:empty)::before {
        margin-right: .25em;
    }

    .divider:not(:empty)::after {
        margin-left: .25em;
    }

    .social-login-buttons {
        display: flex;
        justify-content: space-around;
    }

    .social-login-buttons button {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.2rem;
        width: 48%;
        border: 1px solid #ddd;
        background-color: #fff;
        font-size: 1rem;
        cursor: pointer;
    }

    .social-login-buttons img {
        height: 20px;
        margin-right: 8px;
    }
    h1{
        text-align: center;
        color:blue;
    }
</style>
<div class="container-fluid">
    <div class="content-header sty-one">
        {{-- <h1>HR Login</h1> --}}
    </div>
    <div class="card card-login">
        <div class="login-logo">
            <img src="{{ asset('images/7015971.jpg') }}" alt="Logo">
            <h3class="mb-4">HRMS Login</h3>
        </div>
        {{-- <h5 class="text-center mb-4">Get started with your 15-day free trial.</h5> --}}
        <form action="{{ url('admin/hr/login') }}" method="POST">
            @csrf
            <div class="form-outline">
                <label class="form-label" for="form3Example97">Username</label>
                <input type="text" id="form3Example97" name="emp_username" class="form-control form-control-lg" required />
            </div>
    
            <div class="form-outline">
                <label class="form-label" for="form3ExamplePassword"> Password</label>
                <input type="password" id="form3ExamplePassword" name="emp_password" class="form-control form-control-lg" required />
            </div>
            <button type="submit" class="btn btn-primary btn-lg">GET STARTED</button>
        </form>
    </div>
</div>
@endsection

