@extends('layouts.liff')

@section('css')
<style>
    .breadcrumb {
        background: #caedfd;
        padding: 0.75rem 1rem;
        border-radius: 0.25rem;
    }

    .breadcrumb a {
        color: #000;
    }

    .breadcrumb-item.active {
        color: #fff;
    }

    .breadcrumb-item+.breadcrumb-item::before {
        color: #000;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h2 class="card-title mb-0"><i class="fas fa-stamp me-2"></i>スタンプラリーに登録します</h2>
                </div>
                <div class="card-body">
                    <!-- フォームは最初は非表示 -->
                    <form id="nickname-form" class="form-horizontal" method="POST" action="{{ route('liff.store') }}" style="display:none;">
                        @csrf
                        <div class="mb-3 row">
                            <label for="nickname" class="col-sm-2 col-form-label">ニックネーム</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nickname" name="nickname" placeholder="ニックネーム" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-md-8 offset-md-2">
                                <button type="submit" class="btn btn-primary w-100">登録する</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // LIFFの初期化
    liff.init({ liffId: '{{ env('LIFF_ID') }}' }).then(function () {
        if (!liff.isLoggedIn()) {
            liff.login();
        }
        liff.getProfile().then(function (profile) {
            const uid = profile.userId;
            checkRegistration(uid); // 登録状態のチェック
        });
    });

    // UIDに基づいて登録状態をチェック
    function checkRegistration(uid) {
        fetch('/liff/check', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ uid: uid })
        }).then(function (response) {
            return response.json();
        }).then(function (data) {
            if (data.registered) {
                // 登録済みの場合、info_flgをチェックしてアンケート未回答ならアンケートページへ、回答済みならスタンプページへ遷移
                if (data.info_flg === 0) {
                    window.location.href = '/liff/survey?customer_id=' + data.customerId;
                } else {
                    window.location.href = '/liff/stamp?customer_id=' + data.customerId;
                }
            } else {
                // 未登録の場合、ニックネーム登録フォームを表示
                document.getElementById('nickname-form').style.display = 'block';
            }
        });
    }

    // ニックネーム登録フォームの送信イベント
    document.getElementById('nickname-form').addEventListener('submit', function (event) {
        event.preventDefault();
        const nickname = document.getElementById('nickname').value;
        registerUser(nickname);
    });

    // ユーザー登録
    function registerUser(nickname) {
        liff.getProfile().then(function (profile) {
            const uid = profile.userId;
            fetch('/liff/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ uid: uid, nickname: nickname })
            }).then(function (response) {
                // 登録完了後、アンケートページへ遷移
                window.location.href = '/liff/survey?uid='+uid;
            });
        });
    }
});
</script>
@endsection