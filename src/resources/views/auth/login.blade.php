@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

<body>
    {{-- Header --}}
    <header class="site-header">
        <div class="header-inner">
            <h1 class="site-title">FashionablyLate</h1>

            <div class="header-actions">
                <a class="header-login" href="{{ route('register') }}">register</a>
            </div>
        </div>
    </header>

    {{-- Main --}}
    <main class="auth-main">
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
                    <input id="password" type="password" name="password" class="form-input"
                        placeholder="例: coachtech1106">
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-actions">
                    <button class="submit-btn" type="submit">ログイン</button>
                </div>
            </form>
        </section>
    </main>
</body>
