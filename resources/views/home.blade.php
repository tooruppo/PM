@extends('layouts.layout_menu')

@section('content')

<div id="carouselControls" class="carousel carousel-dark slide" data-bs-ride="carousel" data-bs-interval="1000">
    <ol class="carousel-indicators">
        <li data-bs-target="#carouselIndicators" data-bs-slide-to="0" class="active"></li>
        <li data-bs-target="#carouselIndicators" data-bs-slide-to="1"></li>
        <li data-bs-target="#carouselIndicators" data-bs-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img class="d-block w-100" alt="First slide" src="storage/images/slider_01.webp">
        </div>
        <div class="carousel-item">
            <img class="d-block w-100" alt="Second slide" src="storage/images/slider_02.webp">
        </div>
        <div class="carousel-item">
            <img class="d-block w-100" alt="Third slide" src="storage/images/slider_03.webp">
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselControls" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselControls" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </a>
</div>

@endsection