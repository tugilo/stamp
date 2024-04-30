@extends('adminlte::page')

@section('title', '主催者一覧')

@section('content_header')
    <h1>主催者一覧</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('organizers.create') }}" class="btn btn-primary">新規登録</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>編集</th>
                        <th>ID</th>
                        <th>名前</th>
                        <th>連絡先</th>
                        <th>削除</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($organizers as $organizer)
                        <tr>
                            <td>
                                <a href="{{ route('organizers.edit', $organizer) }}" class="btn btn-primary">編集</a>
                            </td>
                            <td>{{ $organizer->id }}</td>
                            <td>{{ $organizer->name }}</td>
                            <td>{{ $organizer->contact_info }}</td>
                            <td>
                                <form action="{{ route('organizers.destroy', $organizer) }}" method="POST" style="display: inline;">
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