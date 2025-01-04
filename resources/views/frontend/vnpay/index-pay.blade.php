<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Tạo mới đơn hàng</title>
        <link href="{{ asset('assets/vnpay_php/assets/bootstrap.min.css') }}" rel="stylesheet"/>
        <script src="{{ asset('assets/vnpay_php/assets/jquery-1.11.3.min.js') }}"></script>
    </head>

    <body>
        <div class="container">
            <h3>Tạo mới đơn hàng</h3>
            <div class="table-responsive">
                <form action="{{ route('vnpay.createPayment') }}" id="frmCreateOrder" method="post">
                    @csrf
                    
                    <!-- Input Amount -->
                    <div class="form-group">
                        <label for="amount">Số tiền</label>
                        <input class="form-control" data-val="true" data-val-number="The field Amount must be a number." 
                               data-val-required="The Amount field is required." id="amount" max="100000000" 
                               min="1" name="amount" type="number" value="10000" />
                    </div>

                    <!-- Fake Order ID -->
                    <div class="form-group">
                        <label for="order_id">Mã Đơn Hàng (fake)</label>
                        <input class="form-control" id="order_id" name="order_id" type="text" value="QR75ec69f7aa18" />
                    </div>

                    <!-- Fake Total Amount -->
                    <div class="form-group">
                        <label for="totalAmount">Tổng Số Tiền (fake)</label>
                        <input class="form-control" id="totalAmount" name="totalAmount" type="number" value="10000" />
                    </div>

                    <!-- Payment Method -->
                    <h4>Chọn phương thức thanh toán</h4>
                    <div class="form-group">
                        <h5>Cách 1: Chuyển hướng sang Cổng VNPAY chọn phương thức thanh toán</h5>
                        <input type="radio" checked="true" id="vnpayqr" name="bankCode" value="VNPAYQR">
                        <label for="vnpayqr">Cổng thanh toán VNPAYQR</label><br>

                        <h5>Cách 2: Tách phương thức tại site của đơn vị kết nối</h5>
                        <input type="radio" id="vnpayqr_app" name="bankCode" value="VNPAYQR">
                        <label for="vnpayqr_app">Thanh toán bằng ứng dụng hỗ trợ VNPAYQR</label><br>

                        <input type="radio" id="vnbank" name="bankCode" value="VNBANK">
                        <label for="vnbank">Thanh toán qua thẻ ATM/Tài khoản nội địa</label><br>

                        <input type="radio" id="intcard" name="bankCode" value="INTCARD">
                        <label for="intcard">Thanh toán qua thẻ quốc tế</label><br>
                    </div>

                    <!-- Language Selection -->
                    <div class="form-group">
                        <h5>Chọn ngôn ngữ giao diện thanh toán:</h5>
                        <input type="radio" id="vn_language" checked="true" name="language" value="vn">
                        <label for="vn_language">Tiếng việt</label><br>

                        <input type="radio" id="en_language" name="language" value="en">
                        <label for="en_language">Tiếng anh</label><br>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-default">Thanh toán</button>
                </form>
            </div>
            <footer class="footer">
                <p>&copy; VNPAY {{ date('Y') }}</p>
            </footer>
        </div>  
    </body>
</html>
