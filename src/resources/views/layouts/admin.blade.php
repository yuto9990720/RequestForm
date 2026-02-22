<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>('title', 'Admin | FashionablyLate')</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    @yield('css')
</head>

<body>
    <header class="admin-header">
        <div class="admin-header__inner">
            <h1 class="brand">FashionablyLate</h1>


            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn--ghost" type="submit">logout</button>
            </form>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>
