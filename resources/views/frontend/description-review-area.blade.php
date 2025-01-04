<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <style>
        .rating-star i {
            font-size: 24px;
            color: #ccc;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .rating-star i.selected {
            color: #FFD700;
        }

        .image_avatar {
            width: 100px;
        }
    </style>
</head>

<body>
    <div class="description-review-area mb-60px">
        <div class="container">
            <div class="description-review-wrapper">
                <div class="description-review-topbar nav">
                    <a data-bs-toggle="tab" href="#des-details1">Nghe Thử</a>
                    <a class="active" data-bs-toggle="tab" href="#des-details2">Chi Tiết Sản Phẩm</a>
                    <a data-bs-toggle="tab" href="#des-details3">Đánh Giá</a>
                </div>
                <div class="tab-content description-review-bottom">
                    <div id="des-details2" class="tab-pane active">
                        <div class="product-anotherinfo-wrapper">
                            <ul>
                                <li><span>Tên chương trình</span>: {{ $product->name }}</li>
                                <li><span>Ngày phát hành</span>: {{ $product->created_at }}</li>
                                <li><span>Thông Tin Khác</span>: Đĩa nhạc đặc biệt dành cho những tín đồ yêu âm nhạc</li>
                            </ul>
                        </div>
                    </div>
                    <div id="des-details1" class="tab-pane">
                        <div class="product-description-wrapper">
                            <div>
                                @include("frontend.audio.mp-3")
                            </div>
                        </div>
                    </div>
                    <div id="des-details3" class="tab-pane">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="review-wrapper">
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="review-title">
                                    <h3>Để lại Đánh Giá</h3>
                                </div>
                                <div class="review-form">
                                    <form id="review-form">
                                        <div class="star-box">
                                            <span>Đánh giá của bạn:</span>
                                            <div id="rating-star" class="rating-star">
                                                <i class="ion-android-star" data-rating="1"></i>
                                                <i class="ion-android-star" data-rating="2"></i>
                                                <i class="ion-android-star" data-rating="3"></i>
                                                <i class="ion-android-star" data-rating="4"></i>
                                                <i class="ion-android-star" data-rating="5"></i>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <textarea id="review-comment" class="form-control" rows="5" placeholder="Chia sẻ cảm nhận của bạn"></textarea>
                                        </div>
                                    </form>
                                    <div class="form-group">
                                        <button id="submit-review" type="button" class="btn btn-primary">Gửi Đánh Giá</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const productId = {{ $product->id }};
            let selectedRating = 0;

            const stars = document.querySelectorAll("#rating-star i");
            const reviewComment = document.getElementById("review-comment");
            const submitReview = document.getElementById("submit-review");

            stars.forEach((star) => {
                star.addEventListener("click", function () {
                    selectedRating = parseInt(this.dataset.rating, 10);
                    updateStarHighlight(selectedRating);
                });
            });

            function updateStarHighlight(rating) {
                stars.forEach((star, index) => {
                    if (index < rating) {
                        star.classList.add("selected");
                    } else {
                        star.classList.remove("selected");
                    }
                });
            }

            submitReview.addEventListener("click", function () {
                const comment = reviewComment.value.trim();

                if (!selectedRating) {
                    toastr.error("Vui lòng chọn đánh giá sao!", "Lỗi", {
                        timeOut: 3000,
                        hideDuration: 1000,
                        extendedTimeOut: 1000,
                    });
                    return;
                }

                if (!comment) {
                    toastr.error("Vui lòng nhập nhận xét!", "Lỗi", {
                        timeOut: 3000,
                        hideDuration: 1000,
                        extendedTimeOut: 1000,
                    });
                    return;
                }

                axios.post("{{ route('product.comment.add') }}", {
                        product_id: productId,
                        rating: selectedRating,
                        comment,
                    }, {
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        }
                    })
                    .then((response) => {
                        if (response.status === 200) {
                            toastr.success(response.data.message || "Đánh giá của bạn đã được gửi thành công!", "Thành công", {
                                timeOut: 3000,
                                hideDuration: 1000,
                                extendedTimeOut: 1000,
                            });
                            reviewComment.value = "";
                            updateStarHighlight(0);
                            fetchProductReviews();
                        } else {
                            let errorMessage = response.data?.message || "Đã xảy ra lỗi khi gửi đánh giá.";
                            toastr.error(errorMessage, "Lỗi", {
                                timeOut: 3000,
                                hideDuration: 1000,
                                extendedTimeOut: 1000,
                            });
                        }
                    })
                    .catch((error) => {
                        toastr.error(error.response?.data?.message || "Đã xảy ra lỗi khi gửi đánh giá.", "Lỗi", {
                            timeOut: 3000,
                            hideDuration: 1000,
                            extendedTimeOut: 1000,
                        });
                    });
            });

            function fetchProductReviews() {
                axios.get(`/api/product-reviews/${productId}`)
                    .then((response) => {
                        const reviewsContainer = document.querySelector(".review-wrapper");
                        reviewsContainer.innerHTML = "";

                        if (response.data.reviews && response.data.reviews.length > 0) {
                            response.data.reviews.forEach((review) => {
                                const reviewHTML = `
                                    <div class="single-review">
                                        <div class="review-img">
                                            <img class="image_avatar"  src="${review.avatar}" alt="reviewer" />
                                        </div>
                                        <div class="review-content">
                                            <div class="review-top-wrap">
                                                <div class="review-left">
                                                    <div class="review-name"><h4>${review.name}</h4></div>
                                                    <div class="rating-product">${generateStars(review.rating)}</div>
                                                </div>
                                                <div class="review-date"><span>${review.created_at}</span></div>
                                            </div>
                                            <p>${review.comment}</p>
                                        </div>
                                    </div>`;
                                reviewsContainer.insertAdjacentHTML("beforeend", reviewHTML);
                            });
                        } else {
                            reviewsContainer.innerHTML = "<p>Không có đánh giá nào.</p>";
                        }
                    })
                    .catch((error) => console.error("Lỗi khi tải đánh giá:", error));
            }

            function generateStars(rating) {
                return Array.from({
                        length: 5
                    }, (_, index) =>
                    index < rating ?
                    '<i class="ion-android-star"></i>' :
                    '<i class="ion-android-star-outline"></i>'
                ).join("");
            }

            fetchProductReviews();
        });
    </script>
</body>

</html>
