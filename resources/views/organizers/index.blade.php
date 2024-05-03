@extends('adminlte::page')

@section('title', '主催者一覧')

@section('css')
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        #organizers-table td, #organizers-table th {
            white-space: nowrap;
        }
        #organizers-table .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .small-col {
            width: 50px;  /* セルの幅を50pxに固定 */
            max-width: 50px;  /* 最大幅も50pxに設定 */
            text-align: center;
        }
    </style>
@stop

@section('content_header')
    <h1>主催者一覧</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('organizers.create') }}" class="btn btn-primary">新規登録</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="organizers-table">
                <thead>
                    <tr>
                        <th class="small-col">編集</th>
                        <th>ID</th>
                        <th>名前</th>
                        <th>連絡先</th>
                        <th class="small-col">削除</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($organizers as $organizer)
                        <tr>
                            <td class="small-col">
                                <a href="{{ route('organizers.edit', $organizer) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                            </td>
                            <td>{{ $organizer->id }}</td>
                            <td>{{ $organizer->name }}</td>
                            <td>{{ $organizer->contact_info }}</td>
                            <td class="small-col">
                                <form action="{{ route('organizers.destroy', $organizer) }}" method="POST" style="display: inline;">
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
            $('#organizers-table').DataTable({
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
