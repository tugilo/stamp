@extends('layouts.liff')

@section('css')
<style>
    body {
        background-image: url(/images/bg-main.png?timestamp={{ date('YmdHis') }});
    }
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
    .card-custom {
        margin-top: 100px;
        background-color: rgba(255, 255, 255, 0.7);
        border-radius: 5px;
        padding: 5px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    table {
        width: 100%;
        table-layout: fixed;
    }
    .stamp {
        display: block;
        width: 100%;
        height: auto;
    }
    input[type=text] {
        font-size: 17px;
        transform: scale(0.8);
    }
    .alert {
        position: fixed;
        top: 20%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;
        display: none;
        padding: 10px 20px;
        border-radius: 5px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        text-align: center;
        width: 90%;
        max-width: 90%;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .alert button.btn-close {
        margin-left: auto; /* Pushes the button to the far right */
    }
    .applied-message {
        font-size: 18px;
        font-weight: bold;
        color: #a78728;
        margin-top: 10px;
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
            <div class="card card-custom">
                <div class="card-header bg-info text-white">
                    <h2 class="card-title mb-0"><i class="fas fa-stamp me-2"></i>スタンプラリーに登録します</h2>
                </div>
                <div class="card-body">
                    <!-- エラーメッセージのコンテナ -->
                    <div class="message-container">
                        @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                    </div>
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
    // LIFFの初期化とログインの確認
    liff.init({ liffId: '{{ env('LIFF_ID') }}' }).then(function () {
        if (!liff.isLoggedIn()) {
            liff.login();
        } else {
            liff.getProfile().then(function (profile) {
                const uid = profile.userId;
                checkRegistration(uid); // 登録状態のチェック
            });
        }
    });

    // UIDに基づいて登録状態を確認し、適切なページにリダイレクトする
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
                // 登録済みの場合、info_flgに基づき適切なページにリダイレクト
                if (data.info_flg === 0) {
                    window.location.href = '/liff/survey?customer_id=' + data.customerId;
                } else {
                    window.location.href = '/liff/stamp?customer_id=' + data.customerId;
                }
            } else {
                // 未登録の場合、ニックネーム登録フォームを表示
                document.getElementById('nickname-form').style.display = 'block';
            }
        }).catch(function(error) {
            console.error('Error:', error);
        });
    }

    // ニックネーム登録フォームの送信処理
    document.getElementById('nickname-form').addEventListener('submit', function (event) {
        event.preventDefault();
        const nickname = document.getElementById('nickname').value;
        registerUser(nickname);
    });

    // ユーザーを登録し、適切なページにリダイレクトする
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
            return response.json();  // JSONレスポンスをパース
        }).then(function (data) {
            if (data.error) {
                displayError(data.error);
            } else {
                // 成功した場合、サーバーが提供したURLにリダイレクト
                window.location.href = data.redirect;
            }
        }).catch(function (error) {
            console.error('Error:', error);
        });
    });
}

    // エラーメッセージを表示する
    function displayError(errorMessage) {
        const errorContainer = document.createElement('div');
        errorContainer.className = 'alert alert-danger alert-dismissible fade show';
        errorContainer.role = 'alert';
        errorContainer.innerHTML = errorMessage + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';

        const form = document.getElementById('nickname-form');
        form.parentNode.insertBefore(errorContainer, form);
        errorContainer.style.display = 'block'; // エラーメッセージを表示

        setTimeout(function() {
            errorContainer.style.display = 'none'; // 10秒後に非表示
        }, 10000);
    }
});
</script>
@endsection
