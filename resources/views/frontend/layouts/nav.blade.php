<style>
    .small-image {
        width: 100px;
        height: auto;
    }
</style>
<header class="main-header">
    <div class="header-top-nav">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-4 col-md-12">
                    <div class="text-lg-start text-center">
                        <p class="color-white">Chào mừng bạn đến với CDSyncs!</p>
                    </div>
                </div>

                <div class="col-8 d-lg-block d-none">
                    <div class="header-right-nav hover-style-default">
                        <div class="header-top-set-lan-curr d-flex justify-content-end">
                            <div class="header-bottom-set dropdown">
                                <button class="dropdown-toggle header-action-btn hover-style-default color-white"
                                    data-bs-toggle="dropdown">
                                    Tài khoản <i class="ion-ios-arrow-down"></i>
                                </button>
                                <ul class="dropdown-menu" style="width: 150px">
                                    @if (Auth::check())
                                    <li><a class="dropdown-item"
                                            href="{{ route('account') }}">Account:{{ Auth::user()->name }}</a></li>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                    <ul>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('history') }}">Lịch sử mua
                                                hàng</a>
                                        </li>
                                    </ul>
                                    @if(Auth::check() && Auth::user()->role == "admin")
                                    <ul>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('dashboard') }}">Admin</a>
                                        </li>
                                    </ul>
                                    @endif

                                    <ul>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0)"
                                                onclick="document.getElementById('logout-form').submit()">Đăng
                                                xuất</a>
                                        </li>
                                    </ul>
                                    @else
                                    <li><a class="dropdown-item" href="{{ route('login') }}">Đăng nhập</a></li>
                                    <li><a class="dropdown-item" href="{{ route('register') }}">Đăng ký</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="header-navigation sticky-nav d-none d-lg-block">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2 col-sm-2">
                    <div class="logo">
                        <a href="{{ route('index') }}"><img src="{{asset('assets/images/logo/favicon.png')}}" alt="Logo Ecolife"
                                style="width: 120px;margin-left: 50px " /></a>
                    </div>
                </div>

                <div class="col-md-10 col-sm-10">
                    <div class="main-navigation">
                        <ul>
                            <li class="menu-dropdown">
                                <a href="{{ route('index') }}">Trang chủ</a>
                            </li>
                            <li class="menu-dropdown">
                                <a href="{{ route('sanpham.index') }}">Sản phẩm</a>
                            </li>
                            <li class="menu-dropdown">
                                <a href="{{ route('nghesi.index') }}">Nghệ sĩ</a>
                            </li>
                            <li class="menu-dropdown"><a href="{{ route('blog.index') }}">Tin tức</a></li>
                            <li><a href="{{ route('frontend/contact') }}">Liên hệ</a></li>
                            <li class="menu-dropdown">
                                <a href="{{ route('about') }}">Giới thiệu</a>
                            </li>
                        </ul>
                    </div>

                    <div class="header_account_area">
                        @include('component.search.search-global')
                        <div class="contact-link">
                            <div class="phone">
                                <p>Gọi cho chúng tôi:</p>
                                <a href="tel:(+800)345678">(+84)12345678</a>
                            </div>
                        </div>

                        <div class="cart-info d-flex">
                            <div id="cart-toggle" class="mini-cart-warp">
                                <a href="#offcanvas-cart" class="count-cart color-black offcanvas-toggle">
                                    <span id="cart-tag" class="amount-tag">Giỏ hàng</span>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="header-bottom d-lg-none sticky-nav py-3 mobile-navigation">
        <div class="container-fluid">
            <div class="row justify-content-between align-items-center">
                <div class="col-md-3 col-sm-3">
                    <a href="#offcanvas-mobile-menu" class="offcanvas-toggle mobile-menu">
                        <i class="ion-navicon"></i>
                    </a>
                </div>
                <div class="col-md-6 col-sm-4 d-flex justify-content-center">
                    <div class="logo m-0">
                        <a href="{{ route('index') }}"><img src="assets/images/logo/logo.jpg" alt="Logo Ecolife" /></a>
                    </div>
                </div>
                <div class="col-md-3 col-sm-5">

                    <div class="cart-info d-flex m-0 justify-content-end">
                        <div class="header-bottom-set dropdown">
                            <button class="dropdown-toggle border-0 header-action-btn hover-style-default"
                                data-bs-toggle="dropdown"> <i class="ion-person"></i></button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="my-account.html">Tài khoản của tôi</a></li>
                                <li><a class="dropdown-item" href="checkout.html">Thanh toán</a></li>
                                <li><a class="dropdown-item" href="login.html">Đăng nhập</a></li>
                            </ul>
                        </div>
                        <div class="mini-cart-warp">
                            <a href="#offcanvas-cart" class="count-cart color-black offcanvas-toggle">
                                <span class="amount-tag">20.00</span>
                                <span class="item-quantity-tag">02</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</header>
@include('component.search.search-mobile')

<div class="offcanvas-overlay"></div>

{{-- @include('component.cart.offcanvas-cart'); --}}
<div id="offcanvas-cart" class="offcanvas offcanvas-cart hover-style-default">
    <div class="inner">
        <div class="head">
            <span class="title">Giỏ hàng</span>
            <button class="offcanvas-close">×</button>
        </div>
        <div class="body customScroll">
            <ul class="minicart-product-list">
            </ul>
        </div>
        <div class="shopping-cart-total">
            <h4>Tổng phụ : <span id="subtotal">20.00VND</span></h4>
            <h4>Phí vận chuyển : <span>7.00VND</span></h4>
            <h4>Thuế : <span>0.00VND</span></h4>
            <h4 class="shop-total">Tổng cộng : <span id="total">$27.00</span></h4>
        </div>
        <div class="foot">
            <div class="buttons">
                <a href="{{ route('index.cart') }}" class="btn btn-outline-dark current-btn">Trang giỏ hàng</a>
            </div>
        </div>
    </div>
</div>

<div id="offcanvas-mobile-menu" class="offcanvas offcanvas-mobile-menu hover-style-default">
    <button class="offcanvas-close"></button>
    <div class="contact-info d-flex align-items-center justify-content-center color-black py-3">
        <img class="me-3" src="assets/images/icons/mobile-contact.png" alt="">
        <p>Gọi cho chúng tôi:</p>
        <a class="color-black" href="tel:(+800)345678">(+800)345678</a>
    </div>

    <div class="offcanvas-userpanel">
        <ul>
            <li class="offcanvas-userpanel__role">
                <a href="#">Menu <i class="ion-ios-arrow-down"></i></a>
                <ul class="user-sub-menu">
                    <li class="menu-dropdown">
                        <a href="{{ route('index') }}">Trang chủ</a>
                    </li>
                    <li class="menu-dropdown">
                        <a href="{{ route('sanpham.index') }}">Sản phẩm</a>
                    </li>
                    <li class="menu-dropdown">
                        <a href="{{ route('nghesi.index') }}">Nghệ sĩ</a>
                    </li>
                    <li class="menu-dropdown"><a href="{{ route('blog.index') }}">Tin tức</a></li>
                    <li><a href="{{ route('frontend/contact') }}">Liên hệ</a></li>
                    <li class="menu-dropdown">
                        <a href="{{ route('about') }}">Giới thiệu</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    <div class="menu-close"></div>
</div>
<script>
    async function fetchCart() {
        try {
            const response = await fetch('/api/cart');
            const cartData = await response.json();
            renderCart(cartData);
        } catch (error) {
            console.error('Error fetching cart data:', error);
        }
    }
    async function removeFromCart(itemId) {
        try {
            const response = await fetch(`cart/remove?id=${itemId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            if (response.ok) {
                fetchCart();
            } else {
                console.error('Error removing item from cart.');
            }
        } catch (error) {
            console.error('Error removing item from cart:', error);
        }
    }

    function renderCart(cartData) {
        const cartList = document.querySelector('.minicart-product-list');
        cartList.innerHTML = '';
        let subtotal = 0;
        cartData.forEach(item => {
            subtotal += item.quantity * item.amount;
            const listItem = document.createElement('li');
            listItem.innerHTML = `
           <a href="single-product?id=${item.id}" class="image">
    <img src="${item.image}" alt="${item.alt}" class="small-image">
</a>

            <div class="content">
                <a href="single-product?id=${item.id}" class="title">${item.name}</a>
                <span class="quantity-price">${item.quantity} x <span class="amount">${item.amount}</span></span>
                <a href="#" class="remove" onclick="removeFromCart(${item.id})">×</a>
            </div>
        `;
            cartList.appendChild(listItem);
        });
        document.getElementById('subtotal').textContent = `${subtotal.toFixed(2)}`;
        document.getElementById('total').textContent = `${(subtotal + 7).toFixed(2)}`;
    }
    const cart_tag = document.getElementById('cart-toggle');
    cart_tag.addEventListener('click', () => {
        fetchCart();
    });
</script>