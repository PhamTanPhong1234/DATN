@extends('frontend.layouts.app-user')

@section('main')
<body>
    <div class="container mt-4">
        <h2 class="mb-0">Kết quả tìm kiếm: {{ $query ?? 'Không có kết quả' }}</h2>
    </div>
    <div class="container mt-4">
        <div class="shop-top-bar d-flex justify-content-between align-items-center">
            <div class="shop-tab nav mb-res-sm-15">
                <a class="active" href="#shop-1" data-bs-toggle="tab">
                    <i class="fa fa-th show_grid"></i>
                </a>
                <p id="product-count" class="mb-0">Có {{ $products->count() }} Sản Phẩm.</p>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        <div id="shop-1" class="tab-pane active">
            <div class="row product-data" id="product-list">
                @if($products->isNotEmpty())
                    @foreach($products as $product)
                        <div class="col-xl-3 col-md-6 col-lg-4 col-sm-6 col-xs-12 mb-4">
                            <article class="list-product">
                                <div class="img-block position-relative">
                                    <a href="{{ url('single-product?id=' . $product->id) }}" class="thumbnail">
                                        @if($product->images->isNotEmpty())
                                            <img class="first-img w-100" src="{{ $product->images->first()->image_path }}" alt="{{ $product->name ?? 'Sản phẩm không tên' }}" />
                                            <img class="second-img w-100" src="{{ $product->images->first()->image_path }}" alt="{{ $product->name ?? 'Sản phẩm không tên' }}" />
                                        @else
                                            <img class="first-img w-100" src="/path/to/default-image.jpg" alt="Hình ảnh mặc định" />
                                        @endif
                                    </a>
                                    <div class="quick-view position-absolute top-0 end-0 p-2">
                                        <a class="quick_view text-white" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            <i class="ion-ios-search-strong"></i>
                                        </a>
                                    </div>
                                </div>
                                <span>{{ $product->name ?? 'Sản phẩm không tên' }}</span>
                                <ul class="product-flag list-unstyled m-0">
                                    <li class="new text-danger">{{ $product->isNew ? 'Mới' : '' }}</li>
                                </ul>
                                <div class="product-decs mt-2">
                                    <div class="rating-product mb-2" data-rating="{{ $product->rating ?? 0 }}"></div>
                                    <div class="pricing-meta">
                                        <ul class="list-unstyled m-0">
                                            <li class="old-price text-muted">
                                                {{ $product->old_price ? number_format($product->old_price, 0, ',', '.') . ' đ' : '' }}
                                            </li>
                                            <li class="new-price">
                                                {{ $product->price ? number_format($product->price, 0, ',', '.') . ' đ' : 'Liên hệ' }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="add-to-link mt-2">
                                    <ul class="list-unstyled d-flex justify-content-between m-0">
                                        <li><a class="cart-btn text-decoration-none" href="single-product?id={{ $product->id }}" ><i class="fa fa-cart-plus"></i> Thêm vào giỏ</a></li>
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
