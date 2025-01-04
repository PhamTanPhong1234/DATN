@extends('frontend.layouts.app-user')

@section('main')
<section>
    <section class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-content">
                        <h1 class="breadcrumb-heading">Trang Sản Phẩm</h1>
                        <ul class="breadcrumb-links">
                            <li><a href="index.html">Trang Chủ</a></li>
                            <li>Sản Phẩm</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="product-details-area mtb-60px">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-12">
                    <div class="product-details-img product-details-tab">
                        <div class="zoompro-wrap zoompro-2">
                            <div class="zoompro-border zoompro-span">
                                @if($images->isNotEmpty())
                                    <img class="zoompro" src="{{ asset($images[0]->image_path) }}" data-zoom-image="{{ asset($images[0]->image_path) }}" alt="Product Image" />
                                @else
                                    <img class="zoompro" src="default-image-path.jpg" alt="Default Product Image" />
                                @endif
                            </div>
                        </div>
                        <div id="gallery" class="product-dec-slider-2">
                            @foreach($images as $img)
                                <a class="active" data-image="{{ asset($img->image_path) }}" data-zoom-image="{{ asset($img->image_path) }}">
                                    <img src="{{ asset($img->image_path) }}" alt="Product Image" />
                                </a>
                            @endforeach 
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-12">
                    <div class="product-details-content">
                        <h2>{{ $product->name }}</h2>
                        <div class="pricing-meta">
                            <ul>
                                <li class="text-danger old-price not-cut">{{ $product->price }} đ</li>
                            </ul>
                        </div>
                         <p>
                            {{ $product->description }}
                         </p>
                        <div class="pro-details-quality mt-0px">
                            <div class="cart-plus-minus">
                                <input class="cart-plus-minus-box" type="text" name="qtybutton" value="1" />
                            </div>
                            <div id="" class="pro-details-cart btn-hover">
                                <a href="#">+ Thêm vào giỏ hàng</a>
                            </div>
                        </div>
                        <div class="pro-details-wish-com">
                           
                       
                        </div>
                        <div class="pro-details-social-info">
                            <span>Chia sẻ</span>
                            <div class="social-info">
                                <ul>
                                    <li><a href="#"><i class="ion-social-facebook"></i></a></li>
                                    <li><a href="#"><i class="ion-social-twitter"></i></a></li>
                                    <li><a href="#"><i class="ion-social-google"></i></a></li>
                                    <li><a href="#"><i class="ion-social-instagram"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="pro-details-policy">
                            <ul>
                                <li><img src="{{ asset('assets/images/icons/policy.png') }}" alt="policy" /><span>Chính sách bảo mật</span></li>
                                <li><img src="{{ asset('assets/images/icons/policy-2.png') }}" alt="policy" /><span>Chính sách giao hàng</span></li>
                                <li><img src="{{ asset('assets/images/icons/policy-3.png') }}" alt="policy" /><span>Chính sách trả hàng</span></li>
                            </ul>
                        </div>
                    </div>
                   
                </div>
             
            </div>
        </div>
    </section>
    @include("frontend.description-review-area")
</section>
@endsection
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const addToCartBtn = document.querySelector('.pro-details-cart a');
        if (addToCartBtn) {
            addToCartBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const productId = {{ $product->id }};
                const quantity = document.querySelector('.cart-plus-minus-box').value;
                axios.post('{{ route('cart.add') }}', {
                    product_id: productId,
                    quantity: quantity,
                }, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                })
                .then(response => {
                    if (response.data.message) {
                        toastr.success(response.data.message, "Thành công", {
                            timeOut: 3000,
                            hideDuration: 1000,
                            extendedTimeOut: 1000,
                        });
                        console.log(response.data.cart);
                    }
                })
                .catch(error => {
                    if (error.response && error.response.data.message) {
                        toastr.error(error.response.data.message, "Lỗi", {
                            timeOut: 3000,
                            hideDuration: 1000,
                            extendedTimeOut: 1000,
                        });
                    } else {
                        toastr.error('Đã xảy ra lỗi, vui lòng thử lại!', "Lỗi", {
                            timeOut: 3000,
                            hideDuration: 1000,
                            extendedTimeOut: 1000,
                        });
                    }
                    console.error('Lỗi:', error);
                });
            });
        }
    });
</script>
