@extends('adminlte::page')

@section('title', 'ユーザー一覧')

@section('content_header')
    <h1>ユーザー一覧</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('users.create') }}" class="btn btn-primary">新規登録</a>
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>操作</th>
                        <th>ID</th>
                        <th>名前</th>
                        <th>メールアドレス</th>
                        <th>権限</th>
                        <th>メールフラグ</th>
                        <th>削除</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">編集</a>
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
                                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('本当に削除しますか？')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">削除</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop