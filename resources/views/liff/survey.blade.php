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
        border: 2px solid #dc3545; /* 赤色の境界線 */
        background-color: #f8d7da; /* 薄い赤色の背景 */
    }
    .badge{
        margin-left: 5px;
    }
</style>
@endsection

@section('content')
<main class="main">
    <div class="container mb-2">
        @if(!$customer->info_flg)
        <form class="form-horizontal" method="POST" action="{{ route('liff.survey.store') }}">
            @csrf
            <div class="nickname mx-auto">
                <p class="text-bold text-center">{{ $customer->nickname }}様</p>
            </div>
            <div class="card card-outline mb-2">
                <div class="card-header">
                    <h2 class="card-title text-center">アンケート</h2>
                </div>
                <div class="card-body bg-white">
                    <p class="text-danger text-right">※スタンプラリーの前に簡単なアンケートにご協力ください</p>
                    <input type="hidden" id="customer_id" name="customer_id" value="{{ $customer->id }}">

                    <!-- 性別 -->
                    <div class="form-group row">
                        <label for="gender_id" class="col-4 col-form-label">性別<span class="badge bg-danger">必須</span></label>
                        <div class="col-8">
                            <select class="form-control {{ $errors->has('gender_id') ? 'error-field' : '' }}" name="gender_id" id="gender_id">
                                <option value="">-選択してください-</option>
                                @foreach($genders as $gender)
                                <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- 年代 -->
                    <div class="form-group row mt-2">
                        <label for="age_group_id" class="col-4 col-form-label">年代<span class="badge bg-danger">必須</span></label>
                        <div class="col-8">
                            <select class="form-control {{ $errors->has('age_group_id') ? 'error-field' : '' }}" name="age_group_id" id="age_group_id">
                                <option value="">-選択してください-</option>
                                @foreach($ageGroups as $ageGroup)
                                <option value="{{ $ageGroup->id }}">{{ $ageGroup->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- お住まいの場所 -->
                    <div class="form-group row mt-3 align-items-center">
                        <label class="col-4 col-form-label align-middle">お住まいの場所<span class="badge bg-danger">必須</span></label>
                        <div class="col-8">
                            <select class="form-control {{ $errors->has('residence_id') ? 'error-field' : '' }}" name="residence_id" id="residence_id">
                                <option value="">-選択してください-</option>
                                @foreach($residences as $residence)
                                <option value="{{ $residence->id }}">{{ $residence->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- 本キャンペーンを知ったきっかけ -->
                    <div class="form-group row mt-2 align-items-center">
                        <label for="discovery_trigger_id" class="col-4 col-form-label align-middle">本キャンペーンを知ったきっかけ</label>
                        <div class="col-8">
                            <select class="form-control" name="discovery_trigger_id" id="discovery_trigger_id">
                                <option value="">-選択してください-</option>
                                @foreach($discoveryTriggers as $discoveryTrigger)
                                <option value="{{ $discoveryTrigger->id }}">{{ $discoveryTrigger->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- その他のきっかけ -->
                    <div class="form-group row mt-2 align-items-center" id="custom_discovery_response_container" style="display: none;">
                        <label for="custom_discovery_response" class="col-4 col-form-label align-middle">その他のきっかけ</label>
                        <div class="col-8">
                            <textarea class="form-control" name="custom_discovery_response" id="custom_discovery_response" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- 今後公式LINEで欲しい観光情報 -->
                    <div class="form-group row mt-2 align-items-center">
                        <label class="col-4 col-form-label align-middle">今後公式LINEで欲しい観光情報</label>
                        <div class="col-8">
                            @foreach($infoCategories as $infoCategory)
                            <div class="form-check">
                                <input class="form-check-input info-category" type="checkbox" name="info_category_ids[]" id="info_category_{{ $infoCategory->id }}" value="{{ $infoCategory->id }}">
                                <label class="form-check-label" for="info_category_{{ $infoCategory->id }}">
                                    {{ $infoCategory->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- その他の観光情報 -->
                    <div class="form-group row mt-2 align-items-center" id="custom_info_response_container" style="display: none;">
                        <label for="custom_info_response" class="col-4 col-form-label align-middle">その他の観光情報</label>
                        <div class="col-8">
                            <textarea class="form-control" name="custom_info_response" id="custom_info_response" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center my-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> 登録してスタンプラリーを始める
                        </button>
                    </div>
                </div>
            </div>
        </form>
        @endif
    </div>
</main>
@endsection

@section('js')
<script>
    $(document).ready(function() {

        // 本キャンペーンを知ったきっかけ
        $('#discovery_trigger_id').change(function() {
            var selectedValue = $(this).val();
            if (selectedValue == {{ $discoveryTriggers->where('name', 'その他')->first()->id }}) {
                $('#custom_discovery_response_container').show();
            } else {
                $('#custom_discovery_response_container').hide();
                $('#custom_discovery_response').val('');
            }
        });

        // 今後公式LINEで欲しい観光情報
        $('.info-category').change(function() {
            var otherChecked = $('#info_category_{{ $infoCategories->where('name', 'その他')->first()->id }}').is(':checked');
            if (otherChecked) {
                $('#custom_info_response_container').show();
            } else {
                $('#custom_info_response_container').hide();
                $('#custom_info_response').val('');
            }
        });
    });
</script>
@endsection