@extends('layouts.layout')

@section('content')
<div class="signupPage">
    <header class="header">
        <div>アカウントを作成</div>
    </header>
    <div class='container'>

        <form class="form mt-5" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf

            <label for="file_photo" class="rounded-circle userProfileImg">
                <div class="userProfileImg_description">画像をアップロード</div>
                <i class="fas fa-camera fa-3x"></i>
                <input type="file" id="file_photo" name="img_name">
            </label>
            <div class="userImgPreview" id="userImgPreview">
                <img id="thumbnail" class="userImgPreview_content" accept="image/*" src="">
                <p class="userImgPreview_text">画像をアップロード済み</p>
            </div>
            <div class="form-group @error('name')has-error @enderror">
                <label>名前</label>
                <input type="text" name="name" class="form-control" placeholder="名前を入力してください">
                @error('name')
                <span class="errorMessage">
                    {{ $message }}
                </span>
                @enderror
            </div>
            <div class="form-group @error('email')has-error @enderror">
                <label>メールアドレス</label>
                <input type="email" name="email" class="form-control" placeholder="メールアドレスを入力してください">
                @error('email')
                <span class="errorMessage">
                    {{ $message }}
                </span>
                @enderror
            </div>
            <div class="form-group @error('password')has-error @enderror">
                <label>パスワード</label>
                <em>8文字以上入力してください</em>
                <input type="password" name="password" min="8" class="form-control" placeholder="パスワードを入力してください">
                @error('password')
                <span class="errorMessage">
                    {{ $message }}
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label>確認用パスワード</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="パスワードを再度入力してください">
            </div>
            <div class="form-group @error('employee_num')has-error @enderror">
                <label>職番</label>
                <input type="employee_num" name="employee_num" min="6" max="6" class="form-control" placeholder="6桁番号で入力してください">
                @error('employee_num')
                <span class="errorMessage">
                    {{ $message }}
                </span>
                @enderror
            </div>
            <div class="form-group @error('department')has-error @enderror">
                <label>所属（部）</label>
                <input type="department" name="department" class="form-control" placeholder="全角で入力してください">
                @error('department')
                <span class="errorMessage">
                    {{ $message }}
                </span>
                @enderror
            </div>
            <div class="form-group @error('section')has-error @enderror">
                <label>所属（課）</label>
                <input type="section" name="section" class="form-control" placeholder="全角で入力してください">
                @error('section')
                <span class="errorMessage">
                    {{ $message }}
                </span>
                @enderror
            </div>
            <div class="form-group @error('employment_status')has-error @enderror">
                <label>雇用形態</label>
                <input type="employment_status" name="employment_status" class="form-control" placeholder="「正規社員」か「契約社員」のどちらかを入力してください">
                @error('employment_status')
                <span class="errorMessage">
                    {{ $message }}
                </span>
                @enderror
            </div>
            <!-- <div class="form-group">
            <div><label>性別</label></div>
            <div class="form-check form-check-inline">
            <input class="form-check-input" name="sex" value="0" type="radio" id="inlineRadio1" checked>
            <label class="form-check-label" for="inlineRadio1">男</label>
            </div>
            <div class="form-check form-check-inline">
            <input class="form-check-input" name="sex" value="1" type="radio" id="inlineRadio2">
            <label class="form-check-label" for="inlineRadio2">女</label>
            </div>
            </div>
            <div class="form-group @error('self_introduction')has-error @enderror">
                <label>自己紹介文</label>
                <textarea class="form-control" name="self_introduction" rows="10"></textarea>
                @error('self_introduction')
                <span class="errorMessage">
                    {{ $message }}
                </span>
                @enderror
            </div>   -->
    </div>

    <div class="text-center">
        <button type="submit" class="btn submitBtn">はじめる</button>
        <div class="linkToLogin">
            <a href="{{ route('login') }}">ログインはこちら</a>
        </div>
    </div>
    </form>
</div>
</div>
@endsection