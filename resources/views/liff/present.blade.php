@extends('layouts.liff')

@section('css')
<style>
    /* 背景画像の設定 */
    body {
        background-image: url(/images/bg-main.png?timestamp={{ date('YmdHis') }});
    }
    /* カードスタイルの設定 */
    .card-custom {
        background-color: rgba(255, 255, 255, 0.9); // 半透明の白色
        border-radius: 15px; // 角の丸み
        padding: 20px; // 内側の余白
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); // 影の設定
        margin: 20px; // 外側の余白
    }
    /* フォームコントロールのスタイル調整 */
    .form-control {
        border-radius: 5px; // フィールドの角の丸み
        margin-bottom: 15px; // フィールド間の間隔
    }
    /* ラベルのスタイル調整 */
    .form-group label {
        font-weight: bold; // フォントの太さ
        display: block; // ブロックレベルで表示
        margin-bottom: 5px; // ラベルとフィールド間の間隔
    }
    /* ボタンのスタイル調整 */
    .btn-primary {
        width: 100%; // 幅を100%に設定
        padding: 10px; // パディング
        font-size: 18px; // フォントサイズ
    }
    /* アラートのスタイル調整 */
    .alert {
        margin-bottom: 20px; // アラート下の余白
    }
    /* エラーがあったフィールドのスタイル */
    .error-field {
        background-color: #f8d7da; // 薄い赤色の背景
    }
    .badge{
        margin-left: 5px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="card card-custom">
        <div class="card-body">
            <h2 class="card-title">プレゼント応募フォーム</h2>
            <!-- 成功またはエラーメッセージを表示します -->
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <!-- プレゼント応募のためのフォームを表示します -->
            <form method="POST" action="{{ route('liff.stamp.applyForPresent') }}">
                @csrf
                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                <input type="hidden" name="syubetsu_id" value="{{ $syubetsu_id }}">
                <!-- プレゼント選択用のドロップダウンメニュー -->
                <div class="form-group">
                    <label for="present_id">プレゼント選択</label>
                    <select class="form-control" id="present_id" name="present_id" required>
                        @foreach($presents as $present)
                            <option value="{{ $present->id }}">{{ $present->presents_name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- 名前入力フィールド -->
                <div class="form-group">
                    <label for="name">名前<span class="badge bg-danger">必須</span></label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="例：山田太郎" value="{{ old('name', $previousApplication->name ?? '') }}" required>
                </div>
                <!-- カナ名入力フィールド -->
                <div class="form-group">
                    <label for="name_kana">名前（カナ）</label>
                    <input type="text" class="form-control" id="name_kana" name="name_kana" placeholder="例：ヤマダタロウ" value="{{ old('name_kana', $previousApplication->name_kana ?? '') }}" required>
                </div>
                <!-- 電話番号入力フィールド -->
                <div class="form-group">
                    <label for="tel">電話番号<span class="badge bg-danger">必須</span></label>
                    <input type="number" class="form-control" id="tel" name="tel" placeholder="例：09012345678（ハイフンなし）" value="{{ old('tel', $previousApplication->tel ?? '') }}" required>
                </div>
                <!-- メールアドレス入力フィールド -->
                <div class="form-group">
                    <label for="email">メールアドレス<span class="badge bg-danger">必須</span></label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="例：example@example.com" value="{{ old('email', $previousApplication->email ?? '') }}" required>
                </div>
                <!-- 郵便番号入力フィールド -->
                <div class="form-group">
                    <label for="zip">郵便番号<span class="badge bg-danger">必須</span></label>
                    <input type="number" class="form-control" id="zip" name="zip" placeholder="例：1234567（ハイフンなし）" value="{{ old('zip', $previousApplication->zip ?? '') }}" required>
                </div>
                <!-- 都道府県選択ドロップダウン -->
                <div class="form-group">
                    <label for="prefecture">都道府県<span class="badge bg-danger">必須</span></label>
                    <select class="form-control" id="prefecture" name="prefecture" required>
                        <option>選択してください</option>
                        @foreach($prefectures as $prefecture)
                            <option value="{{ $prefecture->prefecture }}" {{ old('prefecture', $previousApplication->prefecture ?? '') == $prefecture->prefecture ? 'selected' : '' }}>
                                {{ $prefecture->prefecture }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- 市区町村入力フィールド -->
                <div class="form-group">
                    <label for="city">市区町村<span class="badge bg-danger">必須</span></label>
                    <input type="text" class="form-control" id="city" name="city" placeholder="例：静岡市葵区" value="{{ old('city', $previousApplication->city ?? '') }}" required>
                </div>
                <!-- 住所入力フィールド -->
                <div class="form-group">
                    <label for="address">住所<span class="badge bg-danger">必須</span></label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="例：日出町9-1" value="{{ old('address', $previousApplication->address ?? '') }}" required>
                </div>
                <!-- 建物名等入力フィールド -->
                <div class="form-group">
                    <label for="building">建物名等</label>
                    <input type="text" class="form-control" id="building" name="building" placeholder="例：静岡ビルディング501" value="{{ old('building', $previousApplication->building ?? '') }}">
                </div>
                <!-- コメント入力エリア -->
                <div class="form-group">
                    <label for="comment">コメント（任意）</label>
                    <textarea class="form-control" id="comment" name="comment" placeholder="その他コメントがあればこちらに記入してください">{{ old('comment') }}</textarea>
                </div>

                <!-- 送信ボタン -->
                <button type="submit" class="btn btn-primary">応募する</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#zip').on('keyup', function() {
        // ハイフンを含んで入力された郵便番号からハイフンを削除
        var zip = $(this).val().replace(/-/g, '').replace(/[^0-9]/g, '');

        // ハイフンを削除した郵便番号をフィールドに再設定
        $(this).val(zip);

        if (zip.length === 7) {
            // 郵便番号が正しく7桁入力されたときのみ住所情報を取得
            $.ajax({
                url: "https://zipcloud.ibsnet.co.jp/api/search",
                dataType: "jsonp",
                data: { zipcode: zip },
                success: function(data) {
                    if (data.results) {
                        // 取得成功した場合、各フィールドにデータを設定
                        $('#prefecture').val(data.results[0].address1);
                        $('#city').val(data.results[0].address2);
                        $('#address').val(data.results[0].address3);
                    } else {
                        // 該当する住所情報が見つからない場合、フィールドをクリアして警告
                        clearAddressFields();
                        alert('該当する住所情報が見つかりませんでした。');
                    }
                },
                beforeSend: function() {
                    // API呼び出し前に住所フィールドをクリア
                    clearAddressFields();
                }
            });
        } else {
            // 郵便番号が7桁未満の場合も住所フィールドをクリア
            clearAddressFields();
        }
    });

    function clearAddressFields() {
        // 都道府県、市区町村、住所のフィールドをクリアする
        $('#prefecture').val('');
        $('#city').val('');
        $('#address').val('');
    }
});
</script>
@endsection