@extends('adminlte::page')

@section('title', 'ユーザー一覧')

@section('css')
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .btn-icon {
            padding: 0;
            width: 30px;
            text-align: center;
        }
        .btn-icon .btn {
            padding: 2px;
            margin: 0;
            width: 100%;
        }
        #users-table th.btn-icon, #users-table td.btn-icon {
            width: 30px;
        }
    </style>
@stop

@section('content_header')
    <h1>ユーザー一覧</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('users.create') }}" class="btn btn-primary">新規登録</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="users-table">
                <thead>
                    <tr>
                        <th class="btn-icon">操作</th>
                        <th>ID</th>
                        <th>名前</th>
                        <th>メールアドレス</th>
                        <th>権限</th>
                        <th>メールフラグ</th>
                        <th class="btn-icon">削除</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                            </td>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role->role_name }}</td>
                            <td>
                                @if ($user->mail_flg === 1)
                                    <span class="badge badge-success">ON</span>
                                @else
                                    <span class="badge badge-danger">OFF</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
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
            $('#users-table').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.11.3/i18n/ja.json"
                },
                responsive: true,
                autoWidth: false,
                scrollX: true,
                columns: [
                    { width: '30px' },
                    null,
                    null,
                    null,
                    null,
                    null,
                    { width: '30px' }
                ]
            });
        });
    </script>
@stop
