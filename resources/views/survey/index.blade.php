@extends('adminlte::page')

@section('title', 'アンケート結果一覧')
@section('content_header')
    <h1>アンケート結果一覧</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('survey.export') }}" class="btn btn-success">
            Excelで出力
        </a>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover" id="survey-results-table">
            <thead>
                <tr>
                    <th>カスタマーID</th>
                    <th>ニックネーム</th>
                    <th>性別</th>
                    <th>年代</th>
                    <th>居住地</th>
                    <th>知ったきっかけ</th>
                    <th>その他の知ったきっかけ</th>
                    <th>観光情報</th>
                    <th>その他の観光情報</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($responses as $response)
                    <tr>
                        <td>{{ $response->customer_id }}</td>
                        <td>{{ $response->nickname }}</td>
                        <td>{{ optional($response->gender)->name ?? 'N/A' }}</td>
                        <td>{{ optional($response->ageGroup)->name ?? 'N/A' }}</td>
                        <td>{{ optional($response->residence)->name ?? 'N/A' }}</td>
                        <td>{{ optional($response->discoveryTrigger)->name ?? 'N/A' }}</td>
                        <td>
                            @if (optional($response->discoveryTrigger)->name == 'その他' && $response->discoveryCustomResponses->isNotEmpty())
                                {{ $response->discoveryCustomResponses->first()->text ?? 'N/A' }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if (!empty($response->info_category_names))
                                {{ implode(', ', $response->info_category_names) }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if ($response->infoCustomResponses->isNotEmpty())
                                {{ $response->infoCustomResponses->first()->text ?? 'N/A' }}
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
<style>
    #survey-results-table th, #survey-results-table td {
        vertical-align: middle;
        white-space: nowrap;
    }
</style>
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#survey-results-table').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Japanese.json"
            }
        });
    });
</script>
@stop
