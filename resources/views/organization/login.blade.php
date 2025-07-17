<form action="{{ route('login.submit') }}" method="POST">
    @csrf
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>

@if ($errors->any())
    <div>{{ $errors->first() }}</div>
@endif
