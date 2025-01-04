@extends('frontend/layouts/app-user')

@section('main')
    <section class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-content">
                        <h1 class="breadcrumb-hrading">Bài viết blog</h1>
                        <ul class="breadcrumb-links">
                            <li><a href="index.html">Trang chủ</a></li>
                            <li>Blog lưới có thanh bên trái</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="shop-category-area blog-grid">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 order-lg-last col-md-12 order-md-first">
                    <div class="blog-posts">
                        <div class="row mb-1">
                            @forelse($posts as $post)
                                <div class="col-md-6 mb-res-sm-30px">
                                    <div class="single-blog-post blog-grid-post">
                                        <div class="blog-post-media">
                                            <div class="blog-image">
                                                <a href="single-news?id={{ $post->id }}">
                                                    <img src="{{ $post->image_path ?? asset('images/default-image.jpg') }}" alt="Blog Image" />
                                                </a>
                                            </div>
                                        </div>
                                        <div class="blog-post-content-inner mt-30">
                                            <h4 class="blog-title"><a href="single-news?id={{ $post->id }}" >{{ $post->title ?? 'Không có tiêu đề' }}</a></h4>
                                            <ul class="blog-page-meta">
                                                <li>
                                                    <a href="#"><i class="ion-person"></i> {{ $post->user->name ?? 'Tác giả không xác định' }}</a>
                                                </li>
                                                <li>
                                                    <a href="#"><i class="ion-calendar"></i>{{ $post->updated_at ? $post->updated_at->format('d/m/Y') : 'Chưa cập nhật' }}</a>
                                                </li>
                                            </ul>
                                            <p>
                                                {{ $post->content ?? 'Nội dung hiện tại chưa được cung cấp.' }}
                                            </p>
                                            <a class="read-more-btn mb-5" href="single-news?id={{ $post->id }}"> Đọc thêm <i class="ion-android-arrow-dropright-circle"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-md-12">
                                    <p>Hiện tại không có bài viết nào để hiển thị.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
