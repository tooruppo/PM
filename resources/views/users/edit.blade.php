@extends('layouts.layout')

@section('content')
<div class="signupPage col-5 mx-auto">
    <header class="header">
        <div>プロフィールを編集</div>
    </header>

    <form class="form mt-5" method="POST" action="/users/update/{{ $user->id }}" enctype="multipart/form-data">
        @csrf

        @error('email')
        <span class="errorMessage">
            {{ $message }}
        </span>
        @enderror

        <label for="file_photo" class="rounded-circle userProfileImg">
            <div class="userProfileImg_description">画像をアップロード</div>
            <i class="fas fa-camera fa-3x"></i>
            <input type="file" id="file_photo" name="img_name">

        </label>
        <div class="userImgPreview" id="userImgPreview">
            <img id="thumbnail" class="userImgPreview_content" accept="image/*" src="">
            <p class="userImgPreview_text">画像をアップロード済み</p>
        </div>
        <div class="form-group">
            <label>名前</label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}">
        </div>
        <div class="form-group">
            <label>メールアドレス</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}">
        </div>
        <div class="form-group">
            <label>職番</label>
            <input type="text" name="employee_num" class="form-control" value="{{ $user->employee_num }}">
        </div>
        <div class="form-group">
            <label>所属（部）</label>
            <input type="text" name="department" class="form-control" value="{{ $user->department }}">
        </div>
        <div class="form-group">
            <label>所属（課）</label>
            <input type="text" name="section" class="form-control" value="{{ $user->section }}">
        </div>
        <div class="form-group">
            <label>雇用形態</label>
            <input type="text" name="employment_status" class="form-control" value="{{ $user->employment_status }}">
        </div>

        <div class="row d-flex justify-content-evenly mb-3">
            <div class="col">
                <a href="/users/jisseki/{{Auth::id()}}">
                    <button class="btn btn-secondary btn-lg w-100" type="submit">
                        <i class="bi bi-pencil" style="font-size:1.5em; color:#fff;"></i>
                        <br><span class="fs-2">変更する</span>
                    </button>
                </a>
            </div>
            <div class="col">
                <a href="/users/show/{{Auth::id()}}">
                    <button class="btn btn-info btn-lg w-100" type="button">
                        <i class="bi bi-x-square" style="font-size:1.5em; color:#fff;"></i>
                        <br><span class="fs-2 text-white">キャンセル</span>
                    </button>
                </a>

            </div>
            <div class="col">
                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <button class="btn btn-danger btn-lg w-100" type="submit"><i class="bi bi-door-closed" style="font-size:1.5em; color:#fff;"></i>
                        <br><span class="fs-2">ログアウト</span>
                    </button>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
    </form>
</div>

@endsection