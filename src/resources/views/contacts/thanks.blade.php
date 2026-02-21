<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FashionablyLate</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
</head>

<body>
    <div class="container">
        <div class="background">Thank you</div>

        <div class="content">
            <p class="message">お問い合わせありがとうございました</p>
            <a class="home-btn" href="{{ route('contacts.index') }}">
                HOME
            </a>
        </div>
    </div>
</body>

</html>
