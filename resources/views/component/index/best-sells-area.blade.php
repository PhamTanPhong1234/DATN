<section class="best-sells-area mb-30px">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title">
                    <h2>Sản Phẩm Bán Chạy Nhất</h2>
                    <p>Thêm các sản phẩm bán chạy vào lineup hàng tuần</p>
                </div>
            </div>
        </div>
        <div id="best-sell-slider" class="best-sell-slider owl-carousel owl-nav-style">
        </div>
    </div>
</section>

<script>
   

    const displayProducts = (products) => {
        const sliderContainer = document.getElementById('best-sell-slider');
        sliderContainer.innerHTML = '';
        products.forEach(product => {
            const productHTML = `
                <article class="list-product">
                    <div class="img-block">
                        <a href="single-product.html" class="thumbnail">
                            <img class="first-img" src="${product.image1}" alt="${product.name}" />
                            <img class="second-img" src="${product.image2}" alt="" />
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
                        <a class="inner-link" href="shop-4-column.html"><span>${product.category}</span></a>
                        <h2><a href="single-product.html" class="product-link">${product.name}</a></h2>
                        <div class="rating-product">
                            ${'★'.repeat(product.rating)}${'☆'.repeat(5 - product.rating)}
                        </div>
                        <div class="pricing-meta">
                            <ul>
                                <li class="old-price not-cut">${product.price}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="add-to-link">
                        <ul>
                            <li class="cart"><a class="cart-btn" href="#">Xem chi tiếttiết </a></li>
                        
                        </ul>
                    </div>
                </article>
            `;
            sliderContainer.innerHTML += productHTML;
        });
        $('.best-sell-slider').owlCarousel('destroy'); 
        $('.best-sell-slider').owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
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
    };

    const fetchProducts = async () => {
        try {
            const response = await fetch('/api/product/best/sell');
            if (!response.ok) {
            }
            const data = await response.json();
            displayProducts(data.ProductsData);
        } catch (error) {
            console.error('error', error);
            displayProducts(productsFake);
        }
    };
    fetchProducts();
</script>
