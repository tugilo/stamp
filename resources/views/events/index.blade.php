@extends('adminlte::page')

@section('title', 'イベント一覧')

@section('css')
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        #events-table td,#events-table th {
            white-space: nowrap;
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
                        <th>編集</th>
                        <th>イベント名</th>
                        <th>コード</th>
                        <th>主催者</th>
                        <th>会場</th>
                        <th>エリア</th>
                        <th>都市</th>
                        <th>開催日</th>
                        <th>終了日</th>
                        <th>削除</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                        <tr>
                            <td>
                                <a href="{{ route('events.edit', $event) }}" class="btn btn-warning">編集</a>
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
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "scrollX": true
            });
        });
    </script>
@stop
