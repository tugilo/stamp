@extends('adminlte::page')

@section('title', 'イベント一覧')

@section('css')
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        #events-table th, #events-table td {
            white-space: nowrap;  // テキストが折り返さないように設定
            padding-top: 8px;  // 上のパディングを調整
            padding-bottom: 8px;  // 下のパディングを調整
            vertical-align: middle;  // 垂直方向の配置を中央に設定
            line-height: 1.42857143;  // 標準の行高でBootstrapのテーブルと一致させる
        }
        .btn-icon {
            width: 30px;  // アイコンボタンの幅を30pxに固定
            padding: 0;  // アイコンボタン内のパディングを削除
        }
        .dataTables_wrapper .dataTables_filter {
            float: none; 
            text-align: left; 
        }
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-top: 0.5rem; 
        }
        .card-body {
            overflow-x: auto;  // カードのボディが内容を超えた場合に横スクロールを可能にする
        }
    </style>
@stop

@section('content_header')
    <h1>イベント一覧</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('events.create') }}" class="btn btn-primary">新規登録</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="events-table">
                <thead>
                    <tr>
                        <th class="btn-icon">編集</th>
                        <th>イベント名</th>
                        <th>コード</th>
                        <th>主催者</th>
                        <th>会場</th>
                        <th>エリア</th>
                        <th>都市</th>
                        <th>開催日</th>
                        <th>終了日</th>
                        <th class="btn-icon">削除</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                        <tr>
                            <td>
                                <a href="{{ route('events.edit', $event) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                            </td>
                            <td>{{ $event->event_name }}</td>
                            <td>{{ $event->code }}</td>
                            <td>{{ $event->organizer->name }}</td>
                            <td>{{ $event->venue->name }}</td>
                            <td>{{ $event->area->name }}</td>
                            <td>{{ $event->city->name }}</td>
                            <td>{{ $event->event_date }}</td>
                            <td>{{ $event->end_date }}</td>
                            <td>
                                <form action="{{ route('events.destroy', $event) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('本当に削除しますか？')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
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
            $('#events-table').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.11.3/i18n/ja.json"
                },
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                scrollX: true,
                pagingType: "simple", // ページネーションをシンプルに
                dom: 'Bfrtip', // 要素の位置（フィルター入力、ページネーションなど）を定義
                buttons: [] // 不要な場合はボタンを無効化
            });
        });
    </script>
@stop
