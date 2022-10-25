@extends('layouts.layout_admin')

@section('admin_content')
<div class='signinPage'>
    <div class='container'>
        <div class='userIcon'>
            <i class="fas fa-user fa-3x"></i>
        </div>
        <h2 class="title">管理者ログイン</h2>
        <div class="col-5 mx-auto">
            <form class="form" method="POST" action="{{ route('admin.login') }}">
                @csrf
                <div class="form-group @error('email')has-error @enderror">
                    <label>メールアドレス</label>
                    <input type="email" name="email" class="form-control" placeholder="メールアドレスを入力してください" autofocus>
                    @error('email')
                    <span class="errorMessage">
                        {{ $message }}
                    </span>
                    @enderror
                </div>

                <div class="form-group @error('password')has-error @enderror">
                    <label>パスワード</label>
                    <input type="password" name="password" class="form-control" placeholder="パスワードを入力してください">
                    @error('password')
                    <span class="errorMessage">
                        {{ $message }}
                    </span>
                    @enderror
                </div>

                <div class="row d-flex justify-content-evenly mt-5 mb-3">
                    <div class="col">
                        <button class="btn btn-primary btn-sm w-100" type="submit">
                            <i class="bi bi-door-open" style="font-size:1.5em; color:#fff;"></i>
                            <br><span class="fs-2">ログイン</span>
                        </button>
                    </div>
                    <div class="col">
                        <a href="/">
                            <button class="btn btn-danger btn-sm w-100" type="button">
                                <i class="bi bi-clock" style="font-size:1.5em; color:#fff;"></i>
                                <br><span class="fs-2">戻る</span>
                            </button>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection