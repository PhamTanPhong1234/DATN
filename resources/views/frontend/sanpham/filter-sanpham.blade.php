@extends('frontend/layouts/app-user')
@section('main')
<section class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-content">
                    <h1 class="breadcrumb-hrading">Trang Cửa Hàng</h1>
                    <ul class="breadcrumb-links">
                        <li><a href="index.html">Trang Chủ</a></li>
                        <li>Cửa Hàng - Thanh bên trái</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="shop-category-area">
    <div class="container">
        <div class="row">
            @include('component.shop-filter-area')
        </div>
    </div>
</div>
@endsection
