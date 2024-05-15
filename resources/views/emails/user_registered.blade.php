<!-- resources/views/emails/user_registered.blade.php -->

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アカウント登録完了</title>
</head>
<body>
    <p>{{ $user->name }}様</p>

    <p>アカウントの登録が完了しました。</p>

    <p>以下の情報を使用してログインしてください:</p>

    <p>
        <strong>メールアドレス:</strong> {{ $user->email }}<br>
        <strong>パスワード:</strong> {{ $password }}<br>
        <strong>ログインURL:</strong> <a href="{{ $loginUrl }}">{{ $loginUrl }}</a>
    </p>

    <p>ご不明な点がございましたら、お問い合わせください。</p>

    <p>よろしくお願いいたします。</p>
</body>
</html>
