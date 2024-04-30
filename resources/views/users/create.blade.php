@extends('adminlte::page')

@section('title', '新規ユーザー登録')

@section('content_header')
    <h1>新規ユーザー登録</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">名前</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name_kana">名前（カナ）</label>
                    <input type="text" name="name_kana" id="name_kana" class="form-control @error('name_kana') is-invalid @enderror" value="{{ old('name_kana') }}">
                    @error('name_kana')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">メールアドレス</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">パスワード</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">パスワード（確認用）</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" required>
                    @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="role_id">権限</label>
                    <select name="role_id" id="role_id" class="form-control @error('role_id') is-invalid @enderror" required>
                        @foreach ($roles as $role)
                            <option value="">{{ __('adminlte::adminlte.Select_a_role') }}</option>
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->role_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="mail_flg">メールフラグ</label>
                    <select name="mail_flg" id="mail_flg" class="form-control @error('mail_flg') is-invalid @enderror" required>
                        <option value="">{{ __('adminlte::adminlte.Select_a_role') }}</option>
                        <option value="1" {{ old('mail_flg') == 1 ? 'selected' : '' }}>有効</option>
                        <option value="0" {{ old('mail_flg') == 0 ? 'selected' : '' }}>無効</option>
                    </select>
                    @error('mail_flg')
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