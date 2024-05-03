@extends('adminlte::page')

@section('title', 'プレゼント編集')

@section('content_header')
    <h1>プレゼント編集</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('presents.update', $present) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="syubetsu_id">プレゼント種別</label>
                <select class="form-control" id="syubetsu_id" name="syubetsu_id">
                    @foreach($syubetsu as $type)
                    <option value="{{ $type->id }}" {{ $type->id === $present->syubetsu_id ? 'selected' : '' }}>{{ $type->subetsu_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="presents_name">プレゼント名</label>
                <input type="text" class="form-control" id="presents_name" name="presents_name" value="{{ $present->presents_name }}" required>
            </div>
            <div class="form-group">
                <label for="comment">コメント</label>
                <textarea class="form-control" id="comment" name="comment">{{ $present->comment }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">更新する</button>
        </form>
    </div>
</div>
@stop
