@extends('layouts.layout')

@section('menu')
<div class="row d-flex justify-content-evenly mb-2">
    <div class="col">
        <a href="{{ route('home') }}">
            <button class="btn btn-primary btn-sm w-100" type="button">
                <i class="bi bi-house" style="font-size:1.5em; color:#fff;"></i>
                <span class="fs-2">ホーム</span>
            </button>
        </a>
    </div>
    <div class="col">
        <a href="/users/kintai/{{Auth::id()}}">
            <button class="btn btn-success btn-sm w-100" type="button">
                <i class="bi bi-clock" style="font-size:1.5em; color:#fff;"></i>
                <span class="fs-2">出退勤打刻</span>
            </button>
        </a>    
    </div>
    <div class="col">
        <a href="/users/jisseki/{{Auth::id()}}">
            <button class="btn btn-secondary btn-sm w-100" type="button">
                <i class="bi bi-bar-chart-line" style="font-size:1.5em; color:#fff;"></i>
                <span class="fs-2">３か月勤怠実績</span>
            </button>
        </a>
    </div>
    <div class="col">
        <a href="/users/show/{{Auth::id()}}">
            <button class="btn btn-info btn-sm w-100" type="button">
                <i class="bi bi-person" style="font-size:1.5em; color:#fff;"></i>
                <span class="fs-2 text-white">プロフィール</span>
            </button>
        </a>

    </div>
    <div class="col">
        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            <button class="btn btn-danger btn-sm w-100" type="submit"><i class="bi bi-door-closed" style="font-size:1.5em; color:#fff;"></i>
                <span class="fs-2">ログアウト</span></button>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
            {{ csrf_field() }}
        </form>
    </div>
</div>
@endsection