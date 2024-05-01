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
                    <form class="form-horizontal" method="POST" action="{{ route('liff.store') }}">
                        @csrf
                        <div class="mb-3 row">
                            <label for="name" class="col-sm-2 col-form-label">ニックネーム</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input id="nickname" type="text" name="nickname" class="form-control" value="{{ old('nickname') }}" placeholder="ニックネーム" autofocus required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-md-8 offset-md-2">
                                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-check me-2"></i>登録する</button>
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
    <!-- Bootstrap5 JSの読み込み -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 必要な要素の取得
            const nicknameForm = document.getElementById('nickname-form');
            const nicknameInput = document.getElementById('nickname');
            const submitNicknameButton = document.getElementById('submit-nickname');
            const registeredMessage = document.getElementById('registered-message');

            // LIFFの初期化
            liff.init({
                liffId: '2004771210-Ye3bejAv' // 自分のLIFF IDに置き換えてください
            }).then(function () {
                // ログインしていない場合はLIFFログインを実行
                if (!liff.isLoggedIn()) {
                    liff.login();
                }

                // ユーザープロファイルの取得
                liff.getProfile().then(function (profile) {
                    const uid = profile.userId;

                    // 登録状態をチェックするためにサーバーにリクエストを送信
                    fetch('/liff/check', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({uid: uid})
                    }).then(function (response) {
                        return response.json();
                    }).then(function (data) {
                        if (data.registered) {
                            // 登録済みの場合は登録済みメッセージを表示
                            registeredMessage.style.display = 'block';
                        } else {
                            // 未登録の場合はニックネーム登録フォームを表示
                            nicknameForm.style.display = 'block';
                        }
                    }).catch(function (error) {
                        console.error('Error:', error);
                    });
                }).catch(function (error) {
                    console.error('Error:', error);
                });
            }).catch(function (error) {
                console.error('LIFF initialization failed:', error);
            });

            // ニックネーム登録フォームの送信イベント
            submitNicknameButton.addEventListener('click', function () {
                const nickname = nicknameInput.value;

                // ユーザープロファイルの取得
                liff.getProfile().then(function (profile) {
                    const uid = profile.userId;

                    // ニックネームとUIDをサーバーに送信
                    fetch('/liff', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({uid: uid, nickname: nickname})
                    }).then(function (response) {
                        // 登録完了後、LIFFのビューにリダイレクトしてUIDを渡す
                        window.location.href = '/liff?uid=' + uid;
                    }).catch(function (error) {
                        console.error('Error:', error);
                    });
                }).catch(function (error) {
                    console.error('Error:', error);
                });
            });
        });
    </script>
@endsection