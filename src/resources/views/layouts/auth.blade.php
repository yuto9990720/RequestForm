<!doctype html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body>
    <header class="site-header">
        <div class="header-inner">
            <h1 class="site-title">FashionablyLate</h1>

            <div class="header-actions">
                @yield('header_action')
            </div>
        </div>
    </header>

    <main class="auth-main">
        @yield('content')
    </main>
</body>

</html>
