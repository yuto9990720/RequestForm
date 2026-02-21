@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
    <div class="container">
        <h2 class="page-title">Contact</h2>
        <form method="POST" action="{{ route('contacts.confirm') }}">
            @csrf

            <div class="form-group">
                <label>お名前<span class="required">※</span></label>
                <div class="form-content flex">
                    <div>
                        <input type="text" name="last_name" placeholder="例: 山田" value="{{ old('last_name') }}">
                        @error('last_name')
                            <div class="error">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div>
                        <input type="text" name="first_name" placeholder="例: 太郎" value="{{ old('first_name') }}">
                        @error('first_name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>性別<span class="required">※</span></label>
                <div class="form-content gender">
                    <label>
                        <input type="radio" name="gender" value="1" {{ old('gender') == 1 ? 'checked' : '' }}>男性
                    </label>
                    <label>
                        <input type="radio" name="gender" value="2" {{ old('gender') == 2 ? 'checked' : '' }}>女性
                    </label>
                    <label>
                        <input type="radio" name="gender" value="3" {{ old('gender') == 3 ? 'checked' : '' }}>その他
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label>メールアドレス<span class="required">※</span></label>
                <div class="form-content">
                    <input type="email" name="email" placeholder="例: text@example.com" value="{{ old('email') }}">
                    @error('email')
                        <div class="error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label>電話番号<span class="required">※</span></label>
                <div class="form-content flex">
                    <input type="text" name="tel1" placeholder="080" value="{{ old('tel1') }}">
                    <span>-</span>
                    <input type="text" name="tel2" placeholder="1234" value="{{ old('tel2') }}">
                    <span>-</span>
                    <input type="text" name="tel3" placeholder="5678" value="{{ old('tel3') }}">
                </div>
            </div>

            <div class="form-group">
                <label>住所<span class="required">※</span></label>
                <div class="form-content">
                    <input type="text" name="address" placeholder="例: 東京都渋谷区千駄ヶ谷1-2-3" value="{{ old('address') }}">
                </div>
            </div>

            <div class="form-group">
                <label>建物名</label>
                <div class="form-content">
                    <input type="text" name="building" placeholder="例: 千駄ヶ谷マンション101" value="{{ old('building') }}">
                </div>
            </div>

            <div class="form-group">
                <label>お問い合わせの種類<span class="required">※</span></label>
                <div class="form-content">
                    <select name="category_id">
                        <option value="">選択してください</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->content }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>お問い合わせ内容<span class="required">※</span></label>
                <div class="form-content">
                    <textarea name="detail" placeholder="お問い合わせ内容をご記載ください" {{ old('detail') }}></textarea>
                </div>
            </div>

            <div class="submit-btn">
                <button type="submit">確認画面</button>
            </div>

        </form>



    </div>
@endsection
