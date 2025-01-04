@extends('frontend/layouts/app-user')

@section('main')
<section class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-content">
                    <h1 class="breadcrumb-hrading">Bài viết Blog</h1>
                    <ul class="breadcrumb-links">
                        <li><a href="index.html">Trang chủ</a></li>
                        <li>Bài viết chi tiết có thanh bên phải</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="shop-category-area single-blog">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-md-12">
                <div class="blog-posts">
                    <div class="single-blog-post blog-grid-post">
                        <div class="blog-post-media">
                            <div class="blog-image single-blog">
                                <a href="#"><img src="{{ $news->image_path }}" alt="blog" /></a>
                            </div>
                        </div>
                        <div class="blog-post-content-inner">
                            <h4 class="blog-title"><a href="#">{{ $news->title }}</a></h4>
                            <ul class="blog-page-meta">
                                <li>
                                    <a href="#"><i class="ion-person"></i> {{ $news->user->name ?? 'Tác giả không xác định' }}</a>
                                </li>
                                <li>
                                    <a href="#"><i class="ion-calendar"></i>{{ $news->updated_at->format('d/m/Y H:i') }}</a>
                                </li>
                            </ul>
                            <p>
                                {{ $news->content ?? 'Nội dung bài viết không có sẵn.' }}
                            </p>
                        </div>
                        <div class="single-post-content">
                           @foreach($singlenews as $single)
                            <p class="quate-speech">
                                <img src="{{ $single->image }}" alt="">
                                <br>
                                {{ $single->content ?? 'Nội dung không có sẵn.' }}
                            </p>
                            @endforeach
                        </div>
                    </div>
                    <!-- single blog post -->
                </div>
                <div class="blog-single-tags-share d-sm-flex justify-content-between">
                    <div class="blog-single-share d-flex">
                        <span class="title">Chia sẻ:</span>
                        <ul class="social">
                            <li>
                                <a href="#"><i class="ion-social-facebook"></i></a>
                            </li>
                            <li>
                                <a href="#"><i class="ion-social-twitter"></i></a>
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
                <div class="blog-related-post">
                    @include("frontend.blog.comment")
                </div>
            </div>
            <div class="col-lg-3 col-md-12 mb-res-md-60px mb-res-sm-60px">
                <div class="left-sidebar">
                    <div class="sidebar-widget mt-40">
                        <div class="main-heading">
                            <h2>Bài viết mới nhất</h2>
                            @foreach($post_news as $postnew)
                                <div class="post-item-container d-flex">
                                    <div class="post-item">
                                        <img src="{{ $postnew->image_path }}" alt="" width="200px">
                                        <p>{{ $postnew->title }}</p>
                                        <a href="single-news?id={{ $postnew->id }}">Xem thêm</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
