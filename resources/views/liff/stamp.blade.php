@extends('layouts.liff')

@section('css')
<style>
    body {
        background-image: url(/images/bg-main.png?timestamp={{ date('YmdHis') }});
    }

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
    .alert {
        position: fixed; /* 位置を固定 */
        top: 25%; /* 上部から50%の位置に表示 */
        left: 50%; /* 左から50%の位置に表示 */
        transform: translate(-50%, -50%); /* X軸とY軸の中央に調整 */
        z-index: 9999; /* 他の要素の上に表示 */
        display: none; /* 初期状態では非表示 */
        padding: 10px 20px;
        border-radius: 5px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        background-color: #f8d7da; /* 背景色（エラー用の明るい赤） */
        color: #721c24; /* テキスト色（暗めの赤） */
        border: 1px solid #f5c6cb; /* ボーダー色（エラー用の薄い赤） */
        text-align: center;
        width: 80%;
        max-width: 80%; /* Adjust width as necessary */
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .alert .btn-close {
        margin-left: auto; /* Pushes the button to the far right */
        padding: 0.5rem 1rem; /* Ensures clickable area is sufficient */
        margin-right: -1rem; /* Adjust this value to move closer to or further from the edge */
    }

    .applied-message {
        font-size: 18px;
        font-weight: bold;
        color: #a78728;
        margin-top: 10px;
    }
    .important-notice {
        background-color: #ffff99; /* 明るい黄色 */
        color: black;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
        font-size: 16px;
    }
    .important-notice strong {
        color: red; /* 注意事項のキーワードを赤色で表示 */
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
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                                        <img src="/images/stamp_{{ $number }}{{ $isStamped ? '_on' : '' }}.png?timestamp={{ date('YmdHis') }}" class="stamp" id="stamp_{{ $number }}">
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
                                <a href="{{ route('liff.stamp.applyPresentForm', ['customer_id' => $customer->id, 'syubetsu_id' => 2]) }}" class="btn btn-success my-2">5,000円相当特産品詰合せへ応募</a>
                                @elseif($customer->applied_for_b_prize)
                                <p class="applied-message">5,000円相当特産品詰合せに応募済みです</p>
                                @endif
                                @if($customer->stamp_count >= 6 && !$customer->applied_for_a_prize)
                                <a href="{{ route('liff.stamp.applyPresentForm', ['customer_id' => $customer->id, 'syubetsu_id' => 1]) }}" class="btn btn-success my-2">10,000円分日本平ホテルギフト券へ応募</a>
                                @elseif($customer->applied_for_a_prize)
                                <p class="applied-message">10,000円分日本平ホテルギフト券に応募済みです</p>
                                @endif
                            </div>

                            <!-- 注意書き -->
                            <div class="text-left mt-2 important-notice">
                                <strong>※必ずご確認下さい</strong><br>
                                ・スタンプ押印期限は、各イベント終了日から3日間です<br>
                                ・抽選申込締切：2024年9月30日（月）<br>
                                ・スタンプを6個集めた場合、全ての賞品へ応募いただけます。<br>
                                ・当選者の発表は賞品の発送をもってかえさせていただきます。
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@if(session('survey_url'))
<!-- アンケートモーダル -->
<div class="modal fade" id="surveyModal" tabindex="-1" aria-labelledby="surveyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="surveyModalLabel">イベント参加ありがとうございます！</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                任意アンケートへのご協力をお願いいたします。
            </div>
            <div class="modal-footer">
                <a href="{{ session('survey_url') }}" class="btn btn-primary" id="surveyLink">アンケートページへ</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('js')
<script src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // アラートメッセージを10秒後に非表示にする
        const alertElement = document.querySelector('.alert');
        if (alertElement) {
            alertElement.style.display = 'block'; // エラーメッセージがある場合は表示
            const timeout = setTimeout(function() {
                alertElement.style.display = 'none'; // 10秒後に非表示
            }, 10000);

            // ボタンで明示的に閉じられた場合、タイマーをクリアする
            document.querySelector('.btn-close').addEventListener('click', function() {
                clearTimeout(timeout);
            });
        }
        // スタンプ成功後、アンケートモーダルを表示
        // モーダルが表示された場合
        @if(session('survey_url'))
        $('#surveyModal').modal('show');
        $('#surveyModal').on('hidden.bs.modal', function (e) {
            // モーダルを閉じるときにセッションをクリア
            clearSession();
        });
        // アンケートリンクをクリックしたとき
        $('#surveyLink').on('click', function(e) {
            e.preventDefault(); // リンクのデフォルト動作を防止
            var url = $(this).attr('href');

            // LIFFの機能を使用して外部ブラウザでリンクを開く
            liff.openWindow({
                url: url,
                external: true // 外部ブラウザで開く
            });

            // モーダルを閉じる
            $('#surveyModal').modal('hide');
            // セッションをクリア
            clearSession();
        });
        // セッションをクリアする関数
        function clearSession() {
            fetch('/liff/clear-session', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => console.log(data));
        }
        @endif
        
    });
</script>
@endsection