@extends('frontend/layouts/app-user')

@section('main')
<section class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-content">
                    <h1 class="breadcrumb-hrading">Trang Liên Hệ</h1>
                    <ul class="breadcrumb-links">
                        <li><a href="index.html">Trang Chủ</a></li>
                        <li>Liên Hệ</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="contact-area mtb-60px">
    <div class="container">
        <div class="contact-map mb-10">
            <div id="map">
                <div class="mapouter">
                    <div class="gmap_canvas">
                        <iframe id="gmap_canvas"
                            src="https://maps.google.com/maps?q=121%20King%20St%2C%20Melbourne%20VIC%203000%2C%20Australia&t=&z=13&ie=UTF8&iwloc=&output=embed"
                            frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                        <a href="https://sites.google.com/view/maps-api-v2/mapv2"></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="custom-row-2">
            <div class="col-lg-4 col-md-5">
                <div class="contact-info-wrap">
                    <div class="single-contact-info">
                        <div class="contact-icon">
                            <i class="fa fa-phone"></i>
                        </div>
                        <div class="contact-info-dec">
                            <p>+012 345 678 102</p>
                            <p>+012 345 678 102</p>
                        </div>
                    </div>
                    <div class="single-contact-info">
                        <div class="contact-icon">
                            <i class="fa fa-globe"></i>
                        </div>
                        <div class="contact-info-dec">
                            <p><a href="#">urname@email.com</a></p>
                            <p><a href="#">urwebsitenaem.com</a></p>
                        </div>
                    </div>
                    <div class="single-contact-info">
                        <div class="contact-icon">
                            <i class="fa fa-map-marker"></i>
                        </div>
                        <div class="contact-info-dec">
                            <p>Địa chỉ ở đây,</p>
                            <p>đường phố, Ngã tư 123.</p>
                        </div>
                    </div>
                    <div class="contact-social">
                        <h3>Theo Dõi Chúng Tôi</h3>
                        <div class="social-info">
                            <ul>
                                <li>
                                    <a href="#"><i class="ion-social-facebook"></i></a>
                                </li>
                                <li>
                                    <a href="#"><i class="ion-social-twitter"></i></a>
                                </li>
                                <li>
                                    <a href="#"><i class="ion-social-youtube"></i></a>
                                </li>
                                <li>
                                    <a href="#"><i class="ion-social-google"></i></a>
                                </li>
                                <li>
                                    <a href="#"><i class="ion-social-instagram"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-7">
                <div class="contact-form">
                    <div class="contact-title mb-30">
                        <h2>Liên Hệ Với Chúng Tôi</h2>
                    </div>
                    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form class="contact-form-style" id="contact-form" action="{{ route('contact.submit') }}" method="post">
    @csrf
    <div class="row">
        <div class="col-lg-6">
            <input name="name" placeholder="Tên*" type="text" required />
        </div>
        <div class="col-lg-6">
            <input name="email" placeholder="Email*" type="email" required />
        </div>
        <div class="col-lg-12">
            <input name="subject" placeholder="Chủ Đề*" type="text" required />
        </div>
        <div class="col-lg-12">
            <textarea name="message" placeholder="Tin Nhắn Của Bạn*" required></textarea>
            <button class="submit" type="submit">GỬI</button>
        </div>
    </div>
</form>

                    
                    <p class="form-messege"></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
