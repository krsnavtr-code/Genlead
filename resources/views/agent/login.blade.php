@extends('main')

@section('title', 'Agent Login')

@section('content')
<div class="content-wrapper">
    <div class="content-header sty-one">
        <h1 style="text-align:center;">Agent Login</h1>
    </div>

    <div class="content">
        <div class="card">
            <div class="card-body">
                {{-- @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif --}}

                <form action="{{ route('agent.login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
