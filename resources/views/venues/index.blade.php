@extends('adminlte::page')

@section('title', '会場一覧')

@section('css')
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .btn-icon {
            padding: 0; /* パディングをゼロに */
            width: 30px; /* アイコンボタンの幅を30pxに固定 */
            text-align: center;
        }
        .btn-icon .btn {
            padding: 2px; /* ボタンの内部パディングを微調整 */
            margin: 0; /* マージンをゼロに */
            width: 100%; /* ボタンの幅をセルに合わせて調整 */
        }
        #venues-table th.btn-icon, #venues-table td.btn-icon {
            width: 30px; /* ヘッダーとセルの幅を揃える */
        }
        #venues-table td, #venues-table th {
            white-space: nowrap;
            vertical-align: middle; /* セルの内容を中央揃えに */
        }
    </style>
    
    @stop

@section('content_header')
    <h1>会場一覧</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('venues.create') }}" class="btn btn-primary">新規登録</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="venues-table">
                <thead>
                    <tr>
                        <th class="btn-icon">編集</th>
                        <th>ID</th>
                        <th>名前</th>
                        <th>住所</th>
                        <th class="btn-icon">削除</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($venues as $venue)
                        <tr>
                            <td>
                                <a href="{{ route('venues.edit', $venue) }}" class="btn btn-warning btn-sm btn-icon">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                            </td>
                            <td>{{ $venue->id }}</td>
                            <td>{{ $venue->name }}</td>
                            <td>{{ $venue->address }}</td>
                            <td>
                                <form action="{{ route('venues.destroy', $venue) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-icon" onclick="return confirm('本当に削除しますか？')">
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
            $('#venues-table').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.11.3/i18n/ja.json"
                },
                responsive: true,
                autoWidth: false,
                scrollX: true,
                columns: [
                    { width: '30px' },  // 編集ボタン
                    null,               // ID
                    null,               // 名前
                    null,               // 住所
                    { width: '30px' }   // 削除ボタン
                ]
            });
        });
    </script>
    @stop
