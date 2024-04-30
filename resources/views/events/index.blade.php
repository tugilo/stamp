@extends('adminlte::page')

@section('title', 'イベント一覧')

@section('content_header')
    <h1>イベント一覧</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('events.create') }}" class="btn btn-primary">新規登録</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>編集</th>
                        <th>イベント名</th>
                        <th>主催者</th>
                        <th>会場</th>
                        <th>エリア</th>
                        <th>都市</th>
                        <th>開催日</th>
                        <th>削除</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                        <tr>
                            <td>
                                <a href="{{ route('events.edit', $event) }}" class="btn btn-primary">編集</a>
                            </td>
                            <td>{{ $event->event_name }}</td>
                            <td>{{ $event->organizer->name }}</td>
                            <td>{{ $event->venue->name }}</td>
                            <td>{{ $event->area->name }}</td>
                            <td>{{ $event->city->name }}</td>
                            <td>{{ $event->event_date }}</td>
                            <td>
                                <form action="{{ route('events.destroy', $event) }}" method="POST" style="display: inline;">
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
