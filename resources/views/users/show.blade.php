@extends('layouts.layout')

@section('content')

<div class='userInfo col-5 mx-auto'>
    <div class='userInfo_img'>
        <img src="storage/images/{{$user -> img_name}}">
    </div>
    <div class='userInfo_name'>{{ $user -> name }}</div>
    <div class='userInfo_employee_num'>職番：{{ $user -> employee_num }}</div>
    <div class="row d-flex justify-content-evenly mb-3 mt-5">
        <div class="col">
            <a href="users/edit/{{$user->id}}">
                <button class="btn btn-secondary btn-lg w-100" type="submit">
                    <i class="bi bi-pencil" style="font-size:1.5em; color:#fff;"></i>
                    <br><span class="fs-2">情報を編集</span>
                </button>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('home') }}">
                <button class="btn btn-info btn-lg w-100" type="button">
                    <i class="bi bi-arrow-90deg-left" style="font-size:1.5em; color:#fff;"></i>
                    <br><span class="fs-2 text-white">戻る</span>
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
</div>

@endsection