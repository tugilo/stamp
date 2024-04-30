@extends('adminlte::page')

@section('title', '主催者新規登録')

@section('content_header')
    <h1>主催者新規登録</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('organizers.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">名前</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="contact_info">連絡先</label>
                    <input type="text" name="contact_info" id="contact_info" class="form-control @error('contact_info') is-invalid @enderror" value="{{ old('contact_info') }}">
                    @error('contact_info')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">登録</button>
            </form>
        </div>
    </div>
@stop