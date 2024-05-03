@extends('adminlte::page')

@section('title', 'イベント参加者数一覧')

@section('css')
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        th, td {
            white-space: nowrap;  // セル内テキストの折り返しを防ぐ
        }
        .dataTables_wrapper .dataTables_filter {
            float: right;  // フィルター入力を右に配置
        }
        .dataTables_wrapper .dataTables_length {
            float: left;  // 長さ選択を左に配置
        }
        .btn-export {
            margin-right: 10px;  // ボタンの右マージンを設定
        }
    </style>
@stop

@section('content_header')
    <h1>イベント参加者数一覧</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('events.export') }}" class="btn btn-success btn-export">エクセルで出力</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="participations-table">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>イベント名</th>
                    <th>主催者</th>
                    <th>会場</th>
                    <th>開催地区</th>
                    <th>開催市町村</th>
                    <th>開催日</th>
                    <th>終了日</th>
                    <th>参加者数</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($events as $event)
                    <tr>
                        <td>{{ $event->id }}</td>
                        <td>{{ $event->event_name }}</td>
                        <td>{{ optional($event->organizer)->name }}</td>
                        <td>{{ optional($event->venue)->name }}</td>
                        <td>{{ optional($event->area)->name }}</td>
                        <td>{{ optional($event->city)->name }}</td>
                        <td>{{ $event->event_date ? $event->event_date->format('Y年m月d日') : '未定' }}</td>
                        <td>{{ $event->end_date ? $event->end_date->format('Y年m月d日') : '当日' }}</td>
                        <td>{{ $event->participations_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop

@section('js')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#participations-table').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.11.3/i18n/ja.json"
                },
                responsive: true,
                autoWidth: false,
                scrollX: true
            });
        });
    </script>
@stop
