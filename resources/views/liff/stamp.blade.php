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
</style>
@endsection

@section('content')
<main class="main">
    <div class="container">
        <div class="stamp-rally mt-1">
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
                        <input type="text" id="event_code" name="event_code" class="form-control" placeholder="イベントコード">
                        <button type="submit" class="btn btn-primary">スタンプを押す</button>
                    </div>
                </div>
            </form>

            <table class="my-2">
                <tr>
                    @for($i = 1; $i <= 3; $i++)
                    <td class="text-center">
                        @if($stamps->where('stamp_number', $i)->isNotEmpty())
                        <img src="/images/stamp_{{ $i }}_on.png" class="stamp" id="stamp_{{ $i }}">
                        @else
                        <img src="/images/stamp_{{ $i }}.png" class="stamp" id="stamp_{{ $i }}">
                        @endif
                    </td>
                    @endfor
                </tr>
                <tr>
                    @for($i = 4; $i <= 6; $i++)
                    <td class="text-center">
                        @if($stamps->where('stamp_number', $i)->isNotEmpty())
                        <img src="/images/stamp_{{ $i }}_on.png" class="stamp" id="stamp_{{ $i }}">
                        @else
                        <img src="/images/stamp_{{ $i }}.png" class="stamp" id="stamp_{{ $i }}">
                        @endif
                    </td>
                    @endfor
                </tr>
                <tr>
                    @for($i = 7; $i <= 9; $i++)
                    <td class="text-center">
                        @if($stamps->where('stamp_number', $i)->isNotEmpty())
                        <img src="/images/stamp_{{ $i }}_on.png" class="stamp" id="stamp_{{ $i }}">
                        @else
                        <img src="/images/stamp_{{ $i }}.png" class="stamp" id="stamp_{{ $i }}">
                        @endif
                    </td>
                    @endfor
                </tr>
            </table>

            <div class="text-center mt-2">
                @if($stamps->where('stamp_number', 4)->isNotEmpty())
                <button type="button" class="btn btn-success" id="btn-apply-prize-4">4つ目のプレゼントに応募する</button>
                @endif

                @if($stamps->where('stamp_number', 9)->isNotEmpty())
                <button type="button" class="btn btn-success" id="btn-apply-prize-9">9つ目のプレゼントに応募する</button>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // プレゼント応募ボタンのクリックイベント
        $('#btn-apply-prize-4').click(function() {
            // 4つ目のプレゼント応募処理を実装
        });

        $('#btn-apply-prize-9').click(function() {
            // 9つ目のプレゼント応募処理を実装
        });
    });
</script>
@endsection