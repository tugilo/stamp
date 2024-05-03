@extends('adminlte::page')

@section('title', '新規プレゼント登録')

@section('content_header')
    <h1>新規プレゼント登録</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('presents.store') }}">
            @csrf
            <div class="form-group">
                <label for="syubetsu_id">プレゼント種別</label>
                <select class="form-control" id="syubetsu_id" name="syubetsu_id">
                    @foreach($syubetsu as $type)
                    <option value="{{ $type->id }}">{{ $type->subetsu_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="presents_name">プレゼント名</label>
                <input type="text" class="form-control" id="presents_name" name="presents_name" required>
            </div>
            <div class="form-group">
                <label for="comment">コメント</label>
                <textarea class="form-control" id="comment" name="comment"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">登録する</button>
        </form>
    </div>
</div>
@stop
