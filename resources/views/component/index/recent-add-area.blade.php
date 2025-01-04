<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
</head>

<body>
    <section class="recent-add-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <h2>Sản phẩm Mới Thêm</h2>
                        <p>Thêm sản phẩm vào danh sách hàng tuần</p>
                    </div>
                </div>
            </div>
            <div class="recent-product-slider owl-carousel owl-nav-style">
                <!-- Sản phẩm sẽ được chèn ở đây -->
            </div>
        </div>
    </section>

    <script>
        // Hàm lấy sản phẩm mới từ API
        function fetchProductNew() {
            axios.get('{{ route('product-new') }}') // Sử dụng axios thay cho fetch
                .then(response => {
                    if (response.data && response.data.ProductsData) {
                        displayProduct(response.data.ProductsData); // Hiển thị sản phẩm nếu có dữ liệu
                    } else {
                        console.error("Không có sản phẩm mới!");
                    }
                })
                .catch(error => {
                    console.error('Có lỗi xảy ra:', error);
                });
        }

        // Hàm hiển thị các sản phẩm
        function displayProduct(products) {
            const productContainer = document.querySelector('.recent-product-slider');
            let productHTML = ''; // Biến chứa HTML của các sản phẩm

            // Lặp qua mảng sản phẩm và tạo HTML cho mỗi sản phẩm
            products.forEach(product => {
                productHTML += `
                    <article class="list-product">
                        <div class="img-block">
                            <a href="single-product?id=${product.id}" class="thumbnail">
                                <img class="first-img" src="${product.image1}" alt="Ảnh sản phẩm 1" />
                                <img class="second-img" src="${product.image2}" alt="Ảnh sản phẩm 2" />
                            </a>
                            <div class="quick-view">
                                <a class="quick_view" href="#" data-link-action="quickview" title="Xem nhanh" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <i class="ion-ios-search-strong"></i>
                                </a>
                            </div>
                        </div>
                        <ul class="product-flag">
                            <li class="new">Mới</li>
                        </ul>
                        <div class="product-decs">
                            <span>${product.category || 'Chưa có danh mục'}</span>
                            <h2><a href="single-product?id=${product.id}" class="product-link">${product.name}</a></h2>
                            <div class="rating-product">
                                ${'★'.repeat(product.rating)}${'☆'.repeat(5 - product.rating)}
                            </div>
                          <div class="pricing-meta">
    <ul>
        <li class="old-price not-cut text-danger">${product.price} VND</li>
    </ul>
</div>

                        </div>
                        <div class="add-to-link">
                            <ul>
                                <li class="cart"><a class="cart-btn" href="single-product?id=${product.id}">Xem chi tiết</a></li>
                               
                            </ul>
                        </div>
                    </article>
                `;
            });

            productContainer.innerHTML = productHTML; // Đưa HTML vào container sản phẩm

            // Khởi động lại OwlCarousel sau khi cập nhật sản phẩm
            initOwlCarousel();
        }

        function initOwlCarousel() {
            if ($('.recent-product-slider').hasClass('owl-loaded')) {
                $('.recent-product-slider').trigger('destroy.owl.carousel');
            }

            $('.recent-product-slider').owlCarousel({
                loop: true,
                margin: 10,
                nav: true,
                items: 5,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 3
                    },
                    1000: {
                        items: 5
                    }
                }
            });
        }
        fetchProductNew();
    </script>
</body>

</html>
