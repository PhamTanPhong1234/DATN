<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Tạo mới đơn hàng</title>
        <link href="{{ asset('vnpay_php/assets/bootstrap.min.css') }}" rel="stylesheet"/>
        <link href="{{ asset('vnpay_php/assets/jumbotron-narrow.css') }}" rel="stylesheet">  
        <script src="{{ asset('vnpay_php/assets/jquery-1.11.3.min.js') }}"></script>
    </head>

    <body>
        <div class="container">
            <h3>Tạo mới đơn hàng</h3>
            <form action="{{ url('/create-payment') }}" method="post">
                @csrf <!-- CSRF Token -->
                <div class="form-group">
                    <label for="amount">Số tiền</label>
                    <input class="form-control" id="amount" name="amount" type="number" value="10000" />
                </div>

                <h4>Chọn phương thức thanh toán</h4>
                <div class="form-group">
                    <input type="radio" checked="true" id="bankCode" name="bankCode" value="">
                    <label for="bankCode">Cổng thanh toán VNPAYQR</label><br>

                    <input type="radio" id="bankCode" name="bankCode" value="VNPAYQR">
                    <label for="bankCode">Thanh toán bằng ứng dụng hỗ trợ VNPAYQR</label><br>

                    <input type="radio" id="bankCode" name="bankCode" value="VNBANK">
                    <label for="bankCode">Thanh toán qua thẻ ATM/Tài khoản nội địa</label><br>

                    <input type="radio" id="bankCode" name="bankCode" value="INTCARD">
                    <label for="bankCode">Thanh toán qua thẻ quốc tế</label><br>
                </div>

                <div class="form-group">
                    <h5>Chọn ngôn ngữ giao diện thanh toán:</h5>
                    <input type="radio" checked="true" name="language" value="vn">
                    <label for="language">Tiếng việt</label><br>

                    <input type="radio" name="language" value="en">
                    <label for="language">Tiếng anh</label><br>
                </div>

                <button type="submit" class="btn btn-default">Thanh toán</button>
            </form>
        </div>
    </body>
</html>
