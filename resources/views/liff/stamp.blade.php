@extends('layouts.liff')

@section('css')
<style>
    /* 追加のCSSスタイルをここに記述 */
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
    .message-container {
        height: 50px; /* 予めエラーメッセージの高さを指定 */
    }
    .alert {
        margin-top: 10px; /* エラーメッセージのマージン調整 */
    }
</style>
@endsection

@section('content')
<main class="main">
    <div class="container">
        <div class="stamp-rally mt-1">
            <div class="message-container">
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
            @if(isset($customer->nickname))
            <div class="nickname mx-auto">
                <p class="text-bold text-center">{{$customer->nickname}}様</p>
            </div>
            @endif

            <hr>
            <form method="post" action="{{ route('liff.stamp.store') }}">
                @csrf
                <input type="hidden" id="customer_id" name="customer_id" value="{{$customer->id}}">
                <div class="form-group">
                    <div class="input-group mx-auto">
                        <input type="number" id="event_code" name="event_code" class="form-control" placeholder="イベントコード" required>
                        <button type="submit" class="btn btn-primary">スタンプを押す</button>
                    </div>
                </div>
            </form>

            <table class="my-2">
                <tr>
                    @foreach($stamps as $number => $isStamped)
                    <td class="text-center">
                        <img src="/images/stamp_{{ $number }}{{ $isStamped ? '_on' : '' }}.png" class="stamp" id="stamp_{{ $number }}">
                    </td>
                    @if($number % 3 == 0) <!-- 3の倍数で改行 -->
                        </tr><tr>
                    @endif
                    @endforeach
                        
                </tr>
            </table>

            <div class="text-center mt-2">
                <!-- プレゼント応募ボタンの条件付き表示 -->
                @if(isset($stamps[4]) && $stamps[4])
                <button type="button" class="btn btn-success" id="btn-apply-prize-4">4つ目のプレゼントに応募する</button>
                @endif
                @if(isset($stamps[9]) && $stamps[9])
                <button type="button" class="btn btn-success" id="btn-apply-prize-9">9つ目のプレゼントに応募する</button>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection

@section('js')
<script src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        liff.init({ liffId: "2004771210-Ye3bejAv" }).then(() => {
            if (!liff.isLoggedIn()) {
                liff.login();
            }
        }).catch((err) => {
            console.error('LIFF Initialization failed', err);
        });

        // アラートメッセージを10秒後に非表示にする
        setTimeout(function() {
            const alertElement = document.getElementById('error-alert');
            if (alertElement) {
                alertElement.style.display = 'none';
            }
        }, 10000);
    });
</script>
@endsection