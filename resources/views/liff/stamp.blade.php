@extends('layouts.liff')

@section('css')
<style>
    /* 追加のCSSスタイルをここに記述 */
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
    .message-container {
        height: 50px; /* 予めエラーメッセージの高さを指定 */
    }
    .alert {
        margin-top: 10px; /* エラーメッセージのマージン調整 */
    }
    .applied-message {
        font-size: 18px;
        font-weight: bold;
        color: #a78728;
        margin-top: 10px;
    }
</style>
@endsection

@section('content')
<main class="main">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-custom">
                    <div class="card-body">
                        <div class="stamp-rally mt-4">
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
                                    @if($number % 3 == 0)
                                    <!-- 3の倍数で改行 -->
                                </tr><tr>
                                    @endif
                                    @endforeach
                                </tr>
                            </table>
                            <div class="text-center mt-2">
                                <!-- プレゼント応募ボタンの条件付き表示 -->
                                @if($customer->stamp_count >= 3 && !$customer->applied_for_b_prize)
                                <a href="{{ route('liff.stamp.applyPresentForm', ['customer_id' => $customer->id, 'syubetsu_id' => 2]) }}" class="btn btn-success">B賞に応募する</a>
                                @elseif($customer->applied_for_b_prize)
                                <p class="applied-message">B賞に応募済みです</p>
                                @endif
                                @if($customer->stamp_count >= 6 && !$customer->applied_for_a_prize)
                                <a href="{{ route('liff.stamp.applyPresentForm', ['customer_id' => $customer->id, 'syubetsu_id' => 1]) }}" class="btn btn-success">A賞に応募する</a>
                                @elseif($customer->applied_for_a_prize)
                                <p class="applied-message">A賞に応募済みです</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
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