@extends('frontend.layouts.app-user')

@section('main')
    <section class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-content">
                        <h1 class="breadcrumb-heading">Trang Thanh Toán</h1>
                        <ul class="breadcrumb-links">
                            <li><a href="{{ route('index') }}">Trang Chủ</a></li>
                            <li>Thanh Toán</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="checkout-area mtb-60px">
        <form class="billing-form-style" id="checkout-form" method="POST">
            @csrf
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-md-7">
                        <div class="billing-info-wrap">
                            <h3>Thông Tin Thanh Toán</h3>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="billing_name">Họ và Tên *</label>
                                        <input id="billing_name" name="name" type="text" class="form-control"
                                            placeholder="Họ và Tên" value="{{ old('name') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="billing_email">Email *</label>
                                        <input id="billing_email" name="email" type="email" class="form-control"
                                            placeholder="Email" value="{{ old('email') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="billing_phone">Số Điện Thoại *</label>
                                        <input id="billing_phone" name="phone" type="tel" class="form-control"
                                            placeholder="Số Điện Thoại" value="{{ old('phone') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="billing_city">Thành Phố *</label>
                                        <input id="billing_city" name="city" type="text" class="form-control"
                                            placeholder="Thành phố" value="{{ old('city') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="billing_district">Huyện *</label>
                                        <input id="billing_district" name="district" type="text" class="form-control"
                                            placeholder="Huyện" value="{{ old('district') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="billing_commune">Thị trấn / Xã</label>
                                        <input id="billing_commune" name="commune" type="text" class="form-control"
                                            placeholder="Thị trấn / Xã" value="{{ old('commune') }}">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="billing_detail_address">Địa chỉ cụ thể</label>
                                        <input id="billing_detail_address" name="address" type="text"
                                            class="form-control" placeholder="Địa chỉ cụ thể"
                                            value="{{ old('detail_address') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="billing_note">Ghi Chú</label>
                                        <textarea id="billing_note" name="note" class="form-control" placeholder="Ghi Chú">{{ old('note') }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="payment_method">Chọn phương thức thanh toán</label>
                                    <div>
                                        <label>
                                            <input type="radio" id="payment_method_1" name="payment_method" value="cash"
                                                {{ old('cash') == 'cash' ? 'checked' : '' }}>
                                            <i class="fas fa-truck"></i> Thanh toán khi nhận hàng
                                        </label>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="radio" id="payment_method_2" name="payment_method" value="atm"
                                                {{ old('atm') == 'atm' ? 'checked' : '' }}>
                                            <i class="fas fa-university"></i> Thanh toán qua ngân hàng
                                        </label>
                                    </div>
                                </div>

                                <div class="mt-5 col-lg-12">
                                    <button class="btn btn-primary" type="submit">Thanh Toán</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-5">
                        <div class="your-order">
                            <h3>Đơn Hàng Của Bạn</h3>
                            <div class="your-order-table table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Sản Phẩm</th>
                                            <th>Tổng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_price = 0;
                                        @endphp
                                        @foreach ($data as $key => $value)
                                            @php
                                                $total_price += $value['price'] * $value['quantity'];
                                            @endphp
                                            <tr>
                                                <td>{{ $value['name'] }} <strong> × {{ $value['quantity'] }}</strong></td>
                                                <td>{{ number_format($value['price'], 0, ',', '.') }} đ</td>
                                                <input type="hidden" name="products[{{ $key }}][id]"
                                                    value="{{ $value['id'] }}">
                                                <input type="hidden" name="products[{{ $key }}][quantity]"
                                                    value="{{ $value['quantity'] }}">
                                                <input type="hidden" name="products[{{ $key }}][price]"
                                                    value="{{ $value['price'] }}">
                                            </tr>
                                        @endforeach
                                    </tbody>

                                    <tfoot>
                                        <tr class="cart-subtotal">
                                            <th>Tổng Tạm</th>
                                            <td>{{ number_format($total_price, 0, ',', '.') }} đ</td>
                                            <input type="hidden" name="total_price" value="{{ $total_price }}">
                                        </tr>
                                        <tr class="shipping">
                                            <th>Phí Vận Chuyển</th>
                                            <td>30,000 đ</td>
                                            <input type="hidden" name="shipping_fee" value="30000">
                                        </tr>
                                        <tr class="order-total">
                                            <th>Tổng Cộng</th>
                                            <td><strong>{{ number_format($total_price + 30000, 0, ',', '.') }} đ</strong>
                                            </td>
                                            <input type="hidden" name="final_price" value="{{ $total_price + 30000 }}">
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </form>
    </div>
@endsection
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById('checkout-form');

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(form);
            const data = {};
            data.products = [];
            formData.forEach((value, key) => {
                const match = key.match(/^products\[(\d+)]\[(.+)]$/);
                if (match) {
                    const index = match[1];
                    const field = match[2];

                    if (!data.products[index]) {
                        data.products[index] = {};
                    }
                    data.products[index][field] = value;
                } else {
                    data[key] = value;
                }
            });
            fetch("{{ route('checkout.submit') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-Token": document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        toastr.success(result.message ||
                            "Đơn hàng của bạn đã được ghi nhận thành công!", "Thành công", {
                                timeOut: 3000,
                                hideDuration: 1000,
                                extendedTimeOut: 1000, 
                            });
                        if (result.paymentUrl) {
                            setTimeout(() => {
                                window.location.href = result.paymentUrl;
                            }, 2000); 
                        }
                    } else {
                        toastr.error(result.message || "Có lỗi xảy ra khi xử lý đơn hàng.", "Lỗi", {
                            timeOut: 3000,
                            hideDuration: 1000,
                            extendedTimeOut: 1000,
                        });
                    }
                })
                .catch(error => {
                    toastr.error("Có lỗi xảy ra. Vui lòng thử lại sau.", "Lỗi", {
                        timeOut: 3000,
                        hideDuration: 1000,
                        extendedTimeOut: 1000,
                    });
                });
        });
    });
</script>
