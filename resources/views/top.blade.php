@extends('layouts.layout')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-5">
            <h2>勤怠管理</h2>
            <div class="row d-flex justify-content-evenly mb-3">
                <div class="col">
                    <a class="text-white" href="{{ route('login') }}">
                        <button class="btn btn-primary btn-sm w-100" type="button">
                            <i class="bi bi-door-open" style="font-size:1.5em; color:#fff;"></i>
                            <span class="fs-2">ログイン(担当者用)</span>
                        </button>
                    </a>
                </div>
                <div class="col">
                    <a class="text-white" href="{{ route('admin.login') }}">
                        <button class="btn btn-success btn-sm w-100" type="button">
                            <i class="bi bi-door-open-fill" style="font-size:1.5em; color:#fff;"></i>
                            <span class="fs-2">ログイン(管理者用)</span>
                        </button>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-5">
            <h2>プロジェクト管理</h2>
            <div class="row d-flex justify-content-evenly mb-3">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <img class="d-block w-100" src="storage/images/slider_01.webp">
        </div>
    </div>
</div>

@endsection