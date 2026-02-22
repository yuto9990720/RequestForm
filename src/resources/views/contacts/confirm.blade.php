@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/confirm.css') }}">
@endsection

<body>
    @section('content')
        <div class="container">
            <h2 class="page-title">Confirm</h2>

            <form method="POST" action="{{ route('contacts.store') }}">
                @csrf

                <table class="confirm-table">
                    <tr>
                        <th>お名前</th>
                        <td>
                            {{ $contact['last_name'] }}　{{ $contact['first_name'] }}
                            <input type="hidden" name="last_name" value="{{ $contact['last_name'] }}">
                            <input type="hidden" name="first_name" value="{{ $contact['first_name'] }}">
                        </td>
                    </tr>

                    <tr>
                        <th>性別</th>
                        <td>
                            @if ($contact['gender'] == 1)
                                男性
                            @elseif($contact['gender'] == 2)
                                女性
                            @elseif($contact['gender'] == 3)
                                その他
                            @endif
                            <input type="hidden" name="gender" value="{{ $contact['gender'] }}">

                        </td>
                    </tr>

                    <tr>
                        <th>メールアドレス</th>
                        <td>
                            {{ $contact['email'] }}
                            <input type="hidden" name="email" value="{{ $contact['email'] }}">
                        </td>
                    </tr>

                    <tr>
                        <th>電話番号</th>
                        <td>
                            {{ $contact['tel'] }}
                            <input type="hidden" name="tel" value="{{ $contact['tel'] }}">
                        </td>
                    </tr>

                    <tr>
                        <th>住所</th>
                        <td>
                            {{ $contact['address'] }}
                            <input type="hidden" name="address" value="{{ $contact['address'] }}">
                        </td>
                    </tr>

                    <tr>
                        <th>建物名</th>
                        <td>
                            {{ $contact['building'] }}
                            <input type="hidden" name="building" value="{{ $contact['building'] }}">
                        </td>
                    </tr>

                    <tr>
                        <th>お問い合わせの種類</th>
                        <td>
                            {{ $category->content }}
                            <input type="hidden" name="category_id" value="{{ $contact['category_id'] }}">
                        </td>
                    </tr>

                    <tr>
                        <th>お問い合わせ内容</th>
                        <td>
                            {!! nl2br($contact['detail']) !!}
                            <input type="hidden" name="detail" value="{{ $contact['detail'] }}">
                        </td>
                    </tr>
                </table>

                <div class="button-wrapper">
                    <button class="submit-btn" type="submit">送信</button>

            </form>
            <form method="POST" action="{{ route('contacts.back') }}">
                @csrf

                <input type="hidden" name="last_name" value="{{ $contact['last_name'] }}">
                <input type="hidden" name="first_name" value="{{ $contact['first_name'] }}">
                <input type="hidden" name="gender" value="{{ $contact['gender'] }}">
                <input type="hidden" name="email" value="{{ $contact['email'] }}">


                <input type="hidden" name="tel1" value="{{ $contact['tel1'] ?? '' }}">
                <input type="hidden" name="tel2" value="{{ $contact['tel2'] ?? '' }}">
                <input type="hidden" name="tel3" value="{{ $contact['tel3'] ?? '' }}">

                <input type="hidden" name="address" value="{{ $contact['address'] }}">
                <input type="hidden" name="building" value="{{ $contact['building'] }}">
                <input type="hidden" name="category_id" value="{{ $contact['category_id'] }}">
                <input type="hidden" name="detail" value="{{ $contact['detail'] }}">

                <button class="return-btn" type="submit">修正</button>
            </form>
        </div>
        </div>
    @endsection
</body>
