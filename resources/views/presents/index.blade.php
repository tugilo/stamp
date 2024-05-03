@extends('adminlte::page')

@section('title', 'プレゼント一覧')

@section('css')
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        #presents-table th.small-col, #presents-table td.small-col {
            width: 50px;  /* セルの幅を50pxに固定 */
            max-width: 50px;  /* 最大幅も50pxに設定 */
            text-align: center;
        }
    </style>
@stop

@section('content_header')
    <h1>プレゼント一覧</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('presents.create') }}" class="btn btn-primary">新規登録</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="presents-table">
            <thead>
                <tr>
                    <th class="small-col">編集</th>
                    <th>プレゼント名</th>
                    <th>種別</th>
                    <th>コメント</th>
                    <th>表示順</th>
                    <th class="small-col">削除</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($presents as $present)
                <tr>
                    <td class="small-col">
                        <a href="{{ route('presents.edit', $present) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                    </td>
                    <td>{{ $present->presents_name }}</td>
                    <td>{{ $present->presentSyubetsu->subetsu_name }}</td>
                    <td>{{ $present->comment }}</td>
                    <td>{{ $present->order_no }}</td>
                    <td class="small-col">
                        <form action="{{ route('presents.destroy', $present->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('このプレゼントを削除しますか？')">
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
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#presents-table').DataTable({
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
