@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')
    <div class="admin-page">
        <main class="admin-main">
            <h2 class="admin-title">Admin</h2>

            @if (session('success'))
                <p style="text-align:center; color:#7a6a5f; margin: 0 0 10px;">{{ session('success') }}</p>
            @endif

            {{-- 検索 --}}
            <form class="search" method="GET" action="{{ route('admin') }}">
                <div class="search__row">
                    <input class="input" type="text" name="keyword" placeholder="名前を入力してください（姓・名・フルネームOK）"
                        value="{{ $filters['keyword'] ?? '' }}">

                    <input class="input" type="text" name="email" placeholder="メールアドレスを入力してください"
                        value="{{ $filters['email'] ?? '' }}">

                    <div class="select-wrap">
                        <select class="select" name="gender">
                            <option value="" @selected(($filters['gender'] ?? '') === '')>性別</option>
                            <option value="all" @selected(($filters['gender'] ?? '') === 'all')>全て</option>
                            <option value="1" @selected(($filters['gender'] ?? '') === '1')>男性</option>
                            <option value="2" @selected(($filters['gender'] ?? '') === '2')>女性</option>
                            <option value="3" @selected(($filters['gender'] ?? '') === '3')>その他</option>
                        </select>
                    </div>

                    <div class="select-wrap">
                        <select class="select" name="category">
                            <option value="" @selected(($filters['category'] ?? '') === '')>お問い合わせの種類</option>
                            <option value="all" @selected(($filters['category'] ?? '') === 'all')>全て</option>
                            @foreach ($categories as $k => $label)
                                <option value="{{ $k }}" @selected(($filters['category'] ?? '') == (string) $k)>{{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <input class="input input--date" type="date" name="date" value="{{ $filters['date'] ?? '' }}">

                    <button class="btn btn--primary" type="submit">検索</button>
                    <a class="btn btn--secondary" href="{{ route('admin') }}">リセット</a>
                </div>

                <div class="search__row search__row--bottom">
                    {{-- エクスポート：検索条件を引き継ぐ --}}
                    <a class="btn btn--export" href="{{ route('admin.export', request()->query()) }}">エクスポート</a>

                    <nav class="pager" aria-label="pagination">

                        {{-- 前へ --}}
                        @if ($contacts->onFirstPage())
                            <span class="pager__btn is-disabled">&lt;</span>
                        @else
                            <a class="pager__btn" href="{{ $contacts->previousPageUrl() }}">&lt;</a>
                        @endif

                        {{-- ページ番号 --}}
                        @foreach ($contacts->getUrlRange(1, $contacts->lastPage()) as $page => $url)
                            @if ($page == $contacts->currentPage())
                                <span class="pager__num is-active">{{ $page }}</span>
                            @else
                                <a class="pager__num" href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- 次へ --}}
                        @if ($contacts->hasMorePages())
                            <a class="pager__btn" href="{{ $contacts->nextPageUrl() }}">&gt;</a>
                        @else
                            <span class="pager__btn is-disabled">&gt;</span>
                        @endif

                    </nav>
                    {{-- ページャ（LaravelのリンクでもOK。要件は「7件ごと」なのでpaginateが満たす） --}}

                </div>
            </form>

            {{-- 一覧 --}}
            <section class="admin-table">
                <div class="table">
                    <div class="table__head">
                        <div class="th">お名前</div>
                        <div class="th">性別</div>
                        <div class="th">メールアドレス</div>
                        <div class="th">お問い合わせの種類</div>
                        <div class="th th--right"></div>
                    </div>

                    @forelse ($contacts as $contact)
                        <div class="table__row">
                            <div class="td">{{ $contact->last_name }} {{ $contact->first_name }}</div>
                            <div class="td">{{ $genders[$contact->gender] ?? '' }}</div>
                            <div class="td">{{ $contact->email }}</div>
                            <div class="td">{{ $categories[$contact->category_id] ?? '' }}</div>

                            <div class="td td--right">
                                <button type="button" class="btn btn--detail js-open-modal"
                                    data-id="{{ $contact->id }}">詳細</button>
                            </div>
                        </div>
                    @empty
                        <div class="table__row">
                            <div class="td" style="grid-column: 1 / -1; text-align:center;">
                                該当データがありません
                            </div>
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- もう一度下にもページネーション（好み） --}}

        </main>
    </div>

    {{-- モーダル（詳細 + 削除） --}}
    <div class="modal" id="detailModal" aria-hidden="true">
        <div class="modal__overlay js-close-modal"></div>

        <div class="modal__content" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
            <button class="modal__close js-close-modal" type="button" aria-label="close">×</button>
            <h3 class="modal__title" id="modalTitle">お問い合わせ詳細</h3>

            <div class="modal__body">
                <dl class="modal__dl">
                    <div class="modal__row">
                        <dt>お名前</dt>
                        <dd id="m_name">-</dd>
                    </div>
                    <div class="modal__row">
                        <dt>性別</dt>
                        <dd id="m_gender">-</dd>
                    </div>
                    <div class="modal__row">
                        <dt>メールアドレス</dt>
                        <dd id="m_email">-</dd>
                    </div>
                    <div class="modal__row">
                        <dt>電話番号</dt>
                        <dd id="m_tel">-</dd>
                    </div>
                    <div class="modal__row">
                        <dt>住所</dt>
                        <dd id="m_address">-</dd>
                    </div>
                    <div class="modal__row">
                        <dt>建物名</dt>
                        <dd id="m_building">-</dd>
                    </div>
                    <div class="modal__row">
                        <dt>お問い合わせの種類</dt>
                        <dd id="m_category">-</dd>
                    </div>
                    <div class="modal__row modal__row--detail">
                        <dt>お問い合わせ内容</dt>
                        <dd id="m_detail">-</dd>
                    </div>
                </dl>
            </div>

            <div class="modal__actions">
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn--secondary" type="submit" onclick="return confirm('削除しますか？')">削除</button>
                </form>
            </div>
        </div>
    </div>

    {{-- JS（モーダル表示 + JSON取得） --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('detailModal');
            const openButtons = document.querySelectorAll('.js-open-modal');
            const closeButtons = document.querySelectorAll('.js-close-modal');

            const setText = (id, text) => {
                const el = document.getElementById(id);
                if (el) el.textContent = text ?? '-';
            };

            const openModal = () => {
                modal.setAttribute('aria-hidden', 'false');
                modal.classList.add('is-open');
            };

            const closeModal = () => {
                modal.setAttribute('aria-hidden', 'true');
                modal.classList.remove('is-open');
            };

            closeButtons.forEach(btn => btn.addEventListener('click', closeModal));
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeModal();
            });

            openButtons.forEach(btn => {
                btn.addEventListener('click', async () => {
                    const id = btn.dataset.id;

                    // 詳細取得（JSON）
                    const res = await fetch(`/admin/contacts/${id}`);
                    if (!res.ok) return;

                    const data = await res.json();

                    setText('m_name', data.name);
                    setText('m_gender', data.gender);
                    setText('m_email', data.email);
                    setText('m_tel', data.tel);
                    setText('m_address', data.address);
                    setText('m_building', data.building);
                    setText('m_category', data.category);
                    setText('m_detail', data.detail);

                    // 削除フォームのaction差し替え
                    const deleteForm = document.getElementById('deleteForm');
                    deleteForm.action = `/admin/contacts/${id}` + window.location.search;

                    openModal();
                });
            });
        });
    </script>
@endsection
