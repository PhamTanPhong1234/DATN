@extends('frontend/layouts/app-user')

@section('main')
    <section class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-content">
                        <h1 class="breadcrumb-hrading">Trang Giới Thiệu</h1>
                        <ul class="breadcrumb-links">
                            <li><a href="index.html">Trang Chủ</a></li>
                            <li>Giới Thiệu</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Khu Vực Breadcrumb Kết Thúc -->

    <!-- Khu Vực Giới Thiệu Bắt Đầu -->
    <section class="about-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-res-sm-50px">
                    <div class="about-left-image">
                        <img src="https://images.pexels.com/photos/5473394/pexels-photo-5473394.jpeg?auto=compress&cs=tinysrgb&w=600" alt="" class="img-responsive" />
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-content">
                        <div class="about-title">
                            <h2>Chào Mừng Đến Với CDSyncs</h2>
                        </div>
                        <p class="mb-30px">
                            Chúng tôi chuyên cung cấp các đĩa CD âm nhạc chất lượng cao, từ những bản nhạc mới nhất đến những album cổ điển yêu thích. Mỗi đĩa CD của chúng tôi đều được sản xuất và kiểm tra kỹ lưỡng để đảm bảo mang đến cho bạn trải nghiệm âm nhạc tuyệt vời nhất.
                        </p>
                        <p>
                            Tại Ecolife, chúng tôi cam kết mang đến cho bạn không chỉ những sản phẩm chất lượng mà còn dịch vụ khách hàng tuyệt vời. Với đội ngũ nhân viên nhiệt tình và đam mê âm nhạc, chúng tôi luôn sẵn sàng giúp bạn tìm được đĩa CD phù hợp với sở thích của mình.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row mt-60px">
                <div class="col-md-4 mb-res-sm-30px">
                    <div class="single-about">
                        <h4>Công Ty Của Chúng Tôi</h4>
                        <p>
                            Ecolife là công ty chuyên cung cấp các đĩa CD âm nhạc với nhiều thể loại khác nhau. Chúng tôi mong muốn mang đến cho khách hàng những sản phẩm âm nhạc chất lượng, giúp bạn khám phá và thưởng thức âm nhạc mọi lúc mọi nơi.
                        </p>
                    </div>
                </div>
                <div class="col-md-4 mb-res-sm-30px">
                    <div class="single-about">
                        <h4>Đội Ngũ Của Chúng Tôi</h4>
                        <p>
                            Đội ngũ của chúng tôi là những người đam mê âm nhạc và có kinh nghiệm lâu năm trong ngành. Chúng tôi luôn tìm kiếm và cung cấp những album CD mới nhất, cũng như những tác phẩm âm nhạc nổi tiếng cho khách hàng.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="single-about">
                        <h4>Ý Kiến Khách Hàng</h4>
                        <p>
                            "Tôi rất hài lòng với sản phẩm của Ecolife. Chất lượng âm thanh tuyệt vời và dịch vụ giao hàng nhanh chóng." - Khách hàng Hà Nội.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
