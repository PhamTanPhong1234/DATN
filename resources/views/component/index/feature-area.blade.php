<section class="feature-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- Tiêu đề Section -->
                <div class="section-title">
                    <h2>Sản Phẩm Nổi Bật</h2>
                    <p>Thêm các sản phẩm vào danh sách hàng tuần</p>
                </div>
                <!-- Tiêu đề Section -->
            </div>
        </div>
        <!-- Feature Slider Start -->
        <div class="feature-slider owl-carousel owl-nav-style" id="product-slider">
            <!-- Dữ liệu sản phẩm sẽ được chèn vào đây bằng JavaScript -->
        </div>
    </div>
</section>

<script>
    const products = [
        {
            name: "Juicy Couture Solid...",
            category: "THIẾT KẾ STUDIO",
            rating: 5,
            price: "€29.90",
            img1: "assets/images/product-image/organic/product-18.jpg",
            img2: "assets/images/product-image/organic/product-18.jpg"
        },
        {
            name: "New Balance Fresh...",
            category: "THIẾT KẾ STUDIO",
            rating: 5,
            price: "€29.90",
            img1: "assets/images/product-image/organic/product-19.jpg",
            img2: "assets/images/product-image/organic/product-20.jpg"
        }
    ];

    const productSlider = document.getElementById('product-slider');
    products.forEach(product => {
        const productHTML = `
            <div class="feature-slider-item">
                <article class="list-product">
                    <div class="img-block">
                        <a href="single-product.html" class="thumbnail">
                            <img class="first-img" src="${product.img1}" alt="${product.name}" />
                            <img class="second-img" src="${product.img2}" alt="${product.name}" />
                        </a>
                        <div class="quick-view">
                            <a class="quick_view" href="#" data-link-action="quickview" title="Xem nhanh" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <i class="ion-ios-search-strong"></i>
                            </a>
                        </div>
                    </div>
                    <div class="product-decs">
                        <a class="inner-link" href="shop-4-column.html"><span>${product.category}</span></a>
                        <h2><a href="single-product.html" class="product-link">${product.name}</a></h2>
                        <div class="rating-product">
                            ${[...Array(product.rating)].map(() => '<i class="ion-android-star"></i>').join('')}
                        </div>
                        <div class="pricing-meta">
                            <ul>
                                <li class="old-price not-cut">${product.price}</li>
                            </ul>
                        </div>
                    </div>
                </article>
            </div>
        `;
        productSlider.innerHTML += productHTML;
    });
</script>
