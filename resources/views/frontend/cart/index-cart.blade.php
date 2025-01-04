@extends('frontend.layouts.app-user')

@section('main')
<section class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-content">
                    <h1 class="breadcrumb-heading">Trang Giỏ Hàng</h1>
                    <ul class="breadcrumb-links">
                        <li><a href="{{ route('index') }}">Trang Chủ</a></li>
                        <li>Giỏ Hàng</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="cart-area mtb-60px">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-7" id="cart-items">
                <!-- Giỏ hàng sẽ được hiển thị ở đây -->
            </div>
            
            <div class="col-lg-4 col-md-5">
                <div id="cart-summary"></div>
                <div class="mt-4 text-end">
                    <a href="/checkout" class="btn btn-primary">Tiến Hành Thanh Toán</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetchCartData();

        // Xử lý sự kiện click trên các nút cập nhật và xóa sản phẩm trong giỏ hàng
        document.getElementById('cart-items').addEventListener('click', function(event) {
            const itemId = event.target.getAttribute('data-id');
            
            if (event.target.classList.contains('decrease-quantity')) {
                updateCartQuantity(itemId, 'decrease');
            } else if (event.target.classList.contains('increase-quantity')) {
                updateCartQuantity(itemId, 'increase');
            } else if (event.target.classList.contains('delete-item')) {
                deleteCartItem(itemId);
            }
        });
    });

    function fetchCartData() {
        fetch("{{ route('cart.data') }}")
            .then(response => response.json())
            .then(data => {
                displayCartItems(data.cart);
                displayCartSummary(data.totalAmount, data.shippingFee);
            })
            .catch(error => console.error('Error fetching cart data:', error));
    }

    function displayCartItems(cartItems) {
        const cartItemsContainer = document.getElementById('cart-items');
        let tableHtml = `
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Hình Ảnh</th>
                        <th>Tên Sản Phẩm</th>
                        <th>Số Lượng</th>
                        <th>Giá</th>
                        <th>Thành Tiền</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
        `;

        cartItems.forEach(item => {
            const product = item.product;
            const firstImage = product.images.length > 0 ? product.images[0].image_path : 'default-image.jpg';

            tableHtml += `
                <tr>
                    <td><img src="/${firstImage}" alt="${product.name}" width="100" height="auto"></td>
                    <td>${product.name}</td>
                    <td>
                        <button class="btn btn-secondary btn-sm decrease-quantity" data-id="${item.id}">-</button>
                        <span class="mx-2">${item.quantity}</span>
                        <button class="btn btn-secondary btn-sm increase-quantity" data-id="${item.id}">+</button>
                    </td>
                    <td>${product.price.toLocaleString()} đ</td>
                    <td>${(product.price * item.quantity).toLocaleString()} đ</td>
                    <td>
                        <button class="btn btn-danger btn-sm delete-item" data-id="${item.id}">Xóa</button>
                    </td>
                </tr>
            `;
        });

        tableHtml += `
                </tbody>
            </table>
        `;

        cartItemsContainer.innerHTML = tableHtml;
    }

    function displayCartSummary(totalAmount, shippingFee) {
        const cartSummaryContainer = document.getElementById('cart-summary');
        cartSummaryContainer.innerHTML = `
            <div class="cart-summary">
                <h4 class="summary-title">Tóm Tắt Giỏ Hàng</h4>
                <ul class="summary-details">
                    <li><span>Tổng Cộng:</span> <span>${totalAmount.toLocaleString()} đ</span></li>
                    <li><span>Phí Vận Chuyển:</span> <span>${shippingFee.toLocaleString()} đ</span></li>
                    <li><strong><span>Thành Tiền:</span> <span>${(totalAmount + shippingFee).toLocaleString()} đ</span></strong></li>
                </ul>
            </div>
        `;
    }

    function updateCartQuantity(itemId, action) {
        fetch(`/cart/update-quantity/${itemId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ action: action })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchCartData(); 
            } else {
                alert('Cập nhật số lượng thất bại!');
            }
        })
        .catch(error => console.error('Error updating cart quantity:', error));
    }

    function deleteCartItem(itemId) {
        fetch(`/cart/remove/${itemId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchCartData();
            } else {
                alert('Xóa sản phẩm thất bại!');
            }
        })
        .catch(error => console.error('Error deleting cart item:', error));
    }
</script>
@endsection
