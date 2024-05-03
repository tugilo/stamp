@extends('layouts.liff')

@section('css')
<style>
    /* 追加のCSSスタイルをここに記述 */
    .card-custom {
        background-color: rgba(255, 255, 255, 0.9);
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin: 20px; /* マージンを追加 */
    }
    .form-control {
        border-radius: 5px;
        margin-bottom: 15px; /* フォームフィールド間の間隔を拡大 */
    }
    .form-group label {
        font-weight: bold; /* ラベルのフォントウェイトを変更 */
        display: block; /* ラベルをブロック表示に */
        margin-bottom: 5px; /* ラベルとフィールド間の間隔 */
    }
    .btn-primary {
        width: 100%; /* ボタンの幅を全幅に */
        padding: 10px; /* ボタンのパディングを追加 */
        font-size: 18px; /* ボタンのフォントサイズを大きく */
    }
    .alert {
        margin-bottom: 20px; /* アラートの下の間隔を広げる */
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="card card-custom">
        <div class="card-body">
            <h2 class="card-title">プレゼント応募フォーム</h2>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('liff.stamp.applyForPresent') }}">
                @csrf
                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                <input type="hidden" name="syubetsu_id" value="{{ $syubetsu_id }}">
                
                <div class="form-group">
                    <label for="present_id">プレゼント選択</label>
                    <select class="form-control" id="present_id" name="present_id" required>
                        @foreach($presents as $present)
                            <option value="{{ $present->id }}">{{ $present->presents_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="name">名前</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $previousApplication->name ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label for="name_kana">名前（カナ）</label>
                    <input type="text" class="form-control" id="name_kana" name="name_kana" value="{{ old('name_kana', $previousApplication->name_kana ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label for="tel">電話番号</label>
                    <input type="text" class="form-control" id="tel" name="tel" value="{{ old('tel', $previousApplication->tel ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label for="email">メールアドレス</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $previousApplication->email ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label for="zip">郵便番号</label>
                    <input type="text" class="form-control" id="zip" name="zip" value="{{ old('zip', $previousApplication->zip ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label for="prefecture">都道府県</label>
                    <select class="form-control" id="prefecture" name="prefecture" required>
                        <option>選択してください</option>
                        @foreach($prefectures as $prefecture)
                            <option value="{{ $prefecture->prefecture }}" {{ old('prefecture', $previousApplication->prefecture ?? '') == $prefecture->prefecture ? 'selected' : '' }}>
                                {{ $prefecture->prefecture }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="city">市区町村</label>
                    <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $previousApplication->city ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label for="address">住所</label>
                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $previousApplication->address ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label for="building">建物名等</label>
                    <input type="text" class="form-control" id="building" name="building" value="{{ old('building', $previousApplication->building ?? '') }}">
                </div>

                <div class="form-group">
                    <label for="comment">コメント（任意）</label>
                    <textarea class="form-control" id="comment" name="comment">{{ old('comment') }}</textarea>
                </div>

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
            var zip = $(this).val().replace(/[^0-9]/g, '');
            if(zip.length === 7) {
                $.ajax({
                    url: "https://zipcloud.ibsnet.co.jp/api/search",
                    dataType: "jsonp",
                    data: {
                        zipcode: zip
                    },
                    success: function(data) {
                        if(data.results) {
                            $('#prefecture').val(data.results[0].address1);
                            $('#city').val(data.results[0].address2);
                            $('#address').val(data.results[0].address3);
                        } else {
                            alert('該当する住所情報が見つかりませんでした。');
                        }
                    }
                });
            }
        });
    });
</script>
@endsection