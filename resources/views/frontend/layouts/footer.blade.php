<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>Ecolife - Cửa Hàng Nhạc Đa Năng</title>
        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon/favicon.png" />
        {{-- <script src="https://js.pusher.com/7.0/pusher.min.js"></script> --}}
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet" />
        
        <!-- Tất cả các tệp CSS -->
        <link rel="stylesheet" href="assets/css/vendor/vendor.min.css">
        <link rel="stylesheet" href="assets/css/plugins/plugins.min.css">
        <link rel="stylesheet" href="assets/css/style.min.css">
        <link rel="stylesheet" href="assets/css/responsive.min.css">
    </head>
    <style>
        #chat-box {
    display: none; /* Ẩn hộp chat mặc định */
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #fff;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    width: 300px;
    max-height: 400px;
    overflow-y: auto;
}

#box-chat {
    font-size: 30px;
    position: fixed;
    bottom: 20px;
    right: 20px;
    cursor: pointer;
    background-color: #007bff;
    color: white;
    padding: 15px;
    border-radius: 50%;
}#closeChatBtn {
    background-color: #f44336; /* Màu đỏ cho nút đóng */
    border: none;
    color: white;
    font-size: 16px;
    cursor: pointer;
}

#closeChatBtn:hover {
    background-color: #d32f2f;
}
    </style>

    <body>
        <footer class="footer-area">
            <div class="footer-top">
                <div class="container">
                    <div class="row">
                        <!-- Widget Footer đơn -->
                        <div class="col-md-6 col-lg-4">
                            <!-- Logo footer -->
                            <div class="footer-logo">
                                <a href="index.html"><img src="assets/images/logo/favicon.png" alt="Logo Ecolife" width="200px"
                                    /></a>
                            </div>
                            <!-- Giới thiệu footer -->
                            <div class="about-footer">
                                <p class="text-info">Chúng tôi cung cấp các dịch vụ và sản phẩm nhạc chất lượng cao cho người yêu âm nhạc.</p>
                                <div class="need-help">
                                    <p class="phone-info">
                                        CẦN GIÚP ĐỠ?
                                        <span>
                                            (+800) 345 678 <br />
                                            (+800) 123 456
                                        </span>
                                    </p>
                                </div>
                                <div class="social-info">
                                    <ul>
                                        <li><a href="#"><i class="ion-social-facebook"></i></a></li>
                                        <li><a href="#"><i class="ion-social-twitter"></i></a></li>
                                        <li><a href="#"><i class="ion-social-youtube"></i></a></li>
                                        <li><a href="#"><i class="ion-social-instagram"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Widget Footer - Thông tin -->
                        <div class="col-md-6 col-lg-2">
                            <div class="single-wedge">
                                <h4 class="footer-herading">Thông Tin</h4>
                                <div class="footer-links">
                                    <ul>
                                        <li><a href="#">Giao Hàng</a></li>
                                        <li><a href="/about">Giới Thiệu Về Chúng Tôi</a></li>
                                        <li><a href="#">Thanh Toán An Toàn</a></li>
                                        <li><a href="/contact">Liên Hệ</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Widget Footer - Liên kết tùy chỉnh -->
                        <div class="col-md-6 col-lg-2">
                            <div class="single-wedge">
                                <h4 class="footer-herading">Liên Kết Tùy Chỉnh</h4>
                                <div class="footer-links">
                                    <ul>
                                        <li><a href="#">Thông Báo Pháp Lý</a></li>
                                        <li><a href="#">Giảm Giá Sản Phẩm</a></li>
                                        <li><a href="#">Sản Phẩm Mới</a></li>
                                        <li><a href="#">Bán Chạy Nhất</a></li>
                                        <li><a href="/login">Đăng Nhập</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Widget Footer - Bản tin -->
                        <div class="col-md-6 col-lg-4">
                            <div class="single-wedge">
                                <h4 class="footer-herading">Bản Tin</h4>
                                <div class="subscrib-text">
                                    <p>Bạn có thể hủy đăng ký bất kỳ lúc nào. Để làm điều đó, vui lòng tìm thông tin liên lạc của chúng tôi trong thông báo pháp lý.</p>
                                </div>
                                <div id="mc_embed_signup" class="subscribe-form">
                                
                                </div>
                                <div class="img_app">
                                    <a href="#"><img src="assets/images/icons/app_store.png" alt="App Store" /></a>
                                    <a href="#"><img src="assets/images/icons/google_play.png" alt="Google Play" /></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>           <div class="footer-bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-lg-5 text-center text-md-start order-2 order-md-1 mt-4 mt-md-0">
                         
                        </div>
                        <div class="col-md-6 col-lg-7 text-center text-md-end order-1 order-md-2">
                            <img class="payment-img" src="assets/images/icons/payment.png" alt="Thanh toán" />
                        </div>
                    </div>
                </div>
            </div>
          @include("message.messages")
    </div>
        </footer>
        <script src="assets/js/vendor/vendor.min.js"></script>
        <script src="assets/js/plugins/plugins.min.js"></script>
        <script src="assets/js/main.js"></script>
    </body>
    
</html>
