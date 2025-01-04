@extends('frontend.layouts.app-user')

@section('main')
<body>
    <div class="container mt-4">
        <h2 class="mb-0">Danh mục > {{ $category->name ?? 'Danh mục không xác định' }}</h2> 
    </div>

    <!-- Shop Top Bar -->
    <div class="container mt-4">
        <div class="shop-top-bar d-flex justify-content-between align-items-center">
            <div class="shop-tab nav mb-res-sm-15">
                <a class="active" href="#shop-1" data-bs-toggle="tab">
                    <i class="fa fa-th show_grid"></i>
                </a>
                <p id="product-count" class="mb-0">Có {{ count($products) }} Sản Phẩm.</p>
            </div>
        </div>
    </div>

    <!-- Product List -->
    <div class="container mt-4">
        <div id="shop-1" class="tab-pane active">
            <div class="row product-data" id="product-list">
                @if(count($products) > 0)
                    @foreach($products as $product)
                        <div class="col-xl-3 col-md-6 col-lg-4 col-sm-6 col-xs-12 mb-4">
                            <article class="list-product">
                                <div class="img-block position-relative">
                                    <a href="{{ url('single-product?id=' . $product['id']) }}" class="thumbnail">
                                        <img class="first-img w-100" src="{{ $product['image'] ?? 'default-image.jpg' }}" alt="{{ $product['name'] ?? 'Sản phẩm không tên' }}" />
                                        <img class="second-img w-100" src="{{ $product['image'] ?? 'default-image.jpg' }}" alt="{{ $product['name'] ?? 'Sản phẩm không tên' }}" />
                                    </a>
                                    <div class="quick-view position-absolute top-0 end-0 p-2">
                                        <a class="quick_view text-white" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            <i class="ion-ios-search-strong"></i>
                                        </a>
                                    </div>
                                </div>
                                <ul class="product-flag list-unstyled m-0">
                                    <li class="new text-danger">{{ $product['isNew'] ? 'Mới' : '' }}</li>
                                </ul>
                                <span>{{  $product['name'] }}</span>
                                <div class="product-decs mt-2">
                                    <div class="rating-product mb-2" data-rating="{{ $product['rating'] ?? 0 }}"></div>
                                    <div class="pricing-meta">
                                        <ul class="list-unstyled m-0">
                                            <li class="new-price text-danger">
                                                {{ number_format($product['price'] ?? 0, 0, ',', '.') }} VND
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="add-to-link mt-2">
                                    <ul class="list-unstyled d-flex justify-content-between m-0">
                                        <li><a class="cart-btn text-decoration-none" href="single-product?id={{ $product['id'] }}"><i class="fa fa-cart-plus"></i>Xem chi tiết</a></li>
                                    </ul>
                                </div>
                            </article>
                        </div>
                    @endforeach
                @else
                    <p>Không có sản phẩm nào.</p>
                @endif
            </div>
        </div>
    </div>
</body>
@endsection
