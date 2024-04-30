@extends('adminlte::page')

@section('title', 'イベント編集')

@section('content_header')
    <h1>イベント編集</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('events.update', $event->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="event_name">イベント名</label>
                    <input type="text" name="event_name" id="event_name" class="form-control @error('event_name') is-invalid @enderror" value="{{ old('event_name', $event->event_name) }}" required autofocus>
                    @error('event_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="organizer_id">主催者</label>
                    <select name="organizer_id" id="organizer_id" class="form-control @error('organizer_id') is-invalid @enderror" required>
                        <option value="">選択してください</option>
                        @foreach ($organizers as $organizer)
                            <option value="{{ $organizer->id }}" {{ old('organizer_id', $event->organizer_id) == $organizer->id ? 'selected' : '' }}>{{ $organizer->name }}</option>
                        @endforeach
                    </select>
                    @error('organizer_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="venue_id">会場</label>
                    <select name="venue_id" id="venue_id" class="form-control @error('venue_id') is-invalid @enderror" required>
                        <option value="">選択してください</option>
                        @foreach ($venues as $venue)
                            <option value="{{ $venue->id }}" {{ old('venue_id', $event->venue_id) == $venue->id ? 'selected' : '' }}>{{ $venue->name }}</option>
                        @endforeach
                    </select>
                    @error('venue_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="area_id">エリア</label>
                    <select name="area_id" id="area_id" class="form-control @error('area_id') is-invalid @enderror" required>
                        <option value="">選択してください</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}" {{ old('area_id', $event->area_id) == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                        @endforeach
                    </select>
                    @error('area_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="city_id">都市</label>
                    <select name="city_id" id="city_id" class="form-control @error('city_id') is-invalid @enderror" required>
                        <option value="">選択してください</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}" {{ old('city_id', $event->city_id) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                        @endforeach
                    </select>
                    @error('city_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="event_date">開催日</label>
                    <input type="date" name="event_date" id="event_date" class="form-control @error('event_date') is-invalid @enderror" value="{{ old('event_date', $event->event_date) }}" required>
                    @error('event_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ the message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="start_time">開始時間</label>
                    <input type="time" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time', $event->start_time) }}" required>
                    @error('start_time')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_time">終了時間</label>
                    <input type="time" name="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time', $event->end_time) }}" required>
                    @error('end_time')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">説明</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $event->description) }}</textarea>
                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="announcement_url">告知URL</label>
                    <input type of="url" name="announcement_url" id="announcement_url" class="form-control @error('announcement_url') is-invalid @enderror" value="{{ old('announcement_url', $event->announcement_url) }}">
                    @error('announcement_url')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="code">コード</label>
                    <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $event->code) }}" maxlength="4" required>
                    @error('code')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="stamp_count">スタンプ数</label>
                    <input type="number" name="stamp_count" id="stamp_count" class="form-control @error('stamp_count') is-invalid @enderror" value="{{ old('stamp_count', $event->stamp_count) }}" min="1" required>
                    @error('stamp_count')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">更新</button>
            </form>
        </div>
    </div>
@stop
