@extends('adminlte::page')

@section('title', '会場一覧')

@section('content_header')
    <h1>会場一覧</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('venues.create') }}" class="btn btn-primary">新規登録</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>編集</th>
                        <th>ID</th>
                        <th>名前</th>
                        <th>住所</th>
                        <th>削除</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($venues as $venue)
                        <tr>
                            <td>
                                <a href="{{ route('venues.edit', $venue) }}" class="btn btn-primary">編集</a>
                            </td>
                            <td>{{ $venue->id }}</td>
                            <td>{{ $venue->name }}</td>
                            <td>{{ $venue->address }}</td>
                            <td>
                                <form action="{{ route('venues.destroy', $venue) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('本当に削除しますか？')">削除</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop