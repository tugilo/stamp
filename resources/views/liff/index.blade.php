<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LIFF App</title>
    <!-- LIFF SDKの読み込み -->
    <script src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>
</head>
<body>
    <h1>LIFF App</h1>

    <!-- ニックネーム登録フォーム（初期状態では非表示） -->
    <div id="nickname-form" style="display: none;">
        <label for="nickname">ニックネーム:</label>
        <input type="text" id="nickname" name="nickname" required>
        <button id="submit-nickname">登録</button>
    </div>

    <!-- 登録済みメッセージ（初期状態では非表示） -->
    <div id="registered-message" style="display: none;">
        <p>既に登録されています。</p>
    </div>

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
                        // 登録完了後の処理
                        window.location.href = '/liff/registered';
                    }).catch(function (error) {
                        console.error('Error:', error);
                    });
                }).catch(function (error) {
                    console.error('Error:', error);
                });
            });
        });
    </script>
</body>
</html>