@extends('layouts.auth')

@section('title', 'Login | FashionablyLate')

@section('header_action')
    <a class="header-login" href="{{ route('register') }}">register</a>
@endsection

@section('content')
    <h2 class="auth-title">Login</h2>

    <section class="auth-card">
        <form class="auth-form" method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="email">メールアドレス</label>
                <input id="email" type="email" name="email" class="form-input" value="{{ old('email') }}"
                    placeholder="例: test@example.com">
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">パスワード</label>
                <input id="password" type="password" name="password" class="form-input" placeholder="例: coachtech1106">
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <button class="submit-btn" type="submit">ログイン</button>
            </div>
        </form>
    </section>
@endsection
