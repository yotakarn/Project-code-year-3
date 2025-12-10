@extends('layouts.main', ['title' => 'Login'])

@section('content')
<main class="login-main">
    <form action="{{ route('authenticate') }}" method="post" class="dentist-form">
        @csrf

        <label for="app-inp-email">
            E-mail
            <input type="email" id="app-inp-email" name="email" required value="{{ old('email') }}" />
        </label>
        @error('email')
          <span></span>
          <span class="error-message">{{ $message }}</span>
        @enderror

        <label for="app-inp-password">
            Password
            <input type="password" id="app-inp-password" name="password" required />
        </label>
        @error('password')
          <span></span>
          <span class="error-message">{{ $message }}</span>
        @enderror

        <div class="login-buttons">
            <button type="submit" class="button primary">Login</button>
        </div>

        <div class="status-message">
            @error('credentials')
            <div role="alert">
                {{ $message }}
            </div>
            @enderror
        </div>
    </form>
</main>
@endsection