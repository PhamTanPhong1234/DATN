<section class="hot-deal-area">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-title">
                            <h2>Ưu Đãi Nóng</h2>
                            <p>Thêm sản phẩm hot vào danh sách hàng tuần</p>
                        </div>
                    </div>
                </div>
                <div class="hot-deal owl-carousel owl-nav-style" id="hot-deals">
                    <!-- Dữ liệu sẽ được đổ vào đây -->
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-8">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-title ml-0px mt-res-sx-30px">
                            <h2>Sản Phẩm Mới</h2>
                            <p>Thêm sản phẩm mới vào danh sách hàng tuần</p>
                        </div>
                    </div>
                </div>
                <div class="new-product-slider owl-carousel owl-nav-style" id="new-arrivals">
                    <!-- Dữ liệu sẽ được đổ vào đây -->
                </div>
            </div>
        </div>
    </div>
</section>
<script>
const hotDeals = [
    {
        name: "Giày Sneaker Casual Thời Trang",
        image1: "assets/images/product-image/organic/product-1.jpg",
        image2: "assets/images/product-image/organic/product-2.jpg",
        priceOld: "€49.90",
        priceNew: "€34.99",
        discount: "-30%",
        link: "single-product.html",
        category: "GÓC ĐỒ HỌA",
        rating: 5,
        inStock: "200 Còn Hàng",
    },
    {
        name: "Áo Khoác Mùa Đông Cho Nam",
        image1: "assets/images/product-image/organic/product-3.jpg",
        image2: "assets/images/product-image/organic/product-4.jpg",
        priceOld: "€99.90",
        priceNew: "€69.99",
        discount: "-30%",
        link: "single-product.html",
        category: "THIẾT KẾ STUDIO",
        rating: 4,
        inStock: "150 Còn Hàng",
    }
];

const newArrivals = [
    {
        name: "Áo Khoác Thể Thao",
        image1: "assets/images/product-image/organic/product-5.jpg",
        image2: "assets/images/product-image/organic/product-6.jpg",
        priceOld: "€79.90",
        priceNew: "€59.99",
        discount: "-25%",
        link: "single-product.html",
        category: "GÓC ĐỒ HỌA",
        rating: 4,
        inStock: "180 Còn Hàng",
    },
    {
        name: "Đồng Hồ Hạng Sang",
        image1: "assets/images/product-image/organic/product-7.jpg",
        image2: "assets/images/product-image/organic/product-8.jpg",
        priceOld: "€199.90",
        priceNew: "€149.99",
        discount: "-25%",
        link: "single-product.html",
        category: "THIẾT KẾ STUDIO",
        rating: 5,
        inStock: "120 Còn Hàng",
    }
];

function renderHotDeals(hotDeals) {
    const hotDealsContainer = document.getElementById("hot-deals");
    hotDeals.forEach(deal => {
        const dealHTML = `
            <article class="list-product">
                <div class="img-block">
                    <a href="${deal.link}" class="thumbnail">
                        <img class="first-img" src="${deal.image1}" alt="${deal.name}" />
                        <img class="second-img" src="${deal.image2}" alt="${deal.name}" />
                    </a>
                    <div class="quick-view">
                        <a class="quick_view" href="#" data-link-action="quickview" title="Xem Nhanh" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <i class="ion-ios-search-strong"></i>
                        </a>
                    </div>
                </div>
                <ul class="product-flag">
                    <li class="new">Mới</li>
                </ul>
                <div class="product-decs">
                    <a class="inner-link" href="shop-4-column.html"><span>${deal.category}</span></a>
                    <h2><a href="${deal.link}" class="product-link">${deal.name}</a></h2>
                    <div class="rating-product">
                        ${'<i class="ion-android-star"></i>'.repeat(deal.rating)}
                    </div>
                    <div class="pricing-meta">
                        <ul>
                            <li class="old-price">${deal.priceOld}</li>
                            <li class="current-price">${deal.priceNew}</li>
                            <li class="discount-price">${deal.discount}</li>
                        </ul>
                    </div>
                    <div class="add-to-link">
                        <ul>
                            <li class="cart"><a class="cart-btn" href="#">Xem chi tiết</a></li>
                            
                        </ul>
                    </div>
                </div>
                <div class="in-stock">Tình Trạng: <span>${deal.inStock}</span></div>
            </article>
        `;
        hotDealsContainer.innerHTML += dealHTML;
    });
}

function renderNewArrivals(newArrivals) {
    const newArrivalsContainer = document.getElementById("new-arrivals");
    newArrivals.forEach(arrival => {
        const arrivalHTML = `
            <div class="product-inner-item">
                <article class="list-product mb-30px">
                    <div class="img-block">
                        <a href="${arrival.link}" class="thumbnail">
                            <img class="first-img" src="${arrival.image1}" alt="${arrival.name}" />
                            <img class="second-img" src="${arrival.image2}" alt="${arrival.name}" />
                        </a>
                        <div class="quick-view">
                            <a class="quick_view" href="#" data-link-action="quickview" title="Xem Nhanh" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <i class="ion-ios-search-strong"></i>
                            </a>
                        </div>
                    </div>
                    <ul class="product-flag">
                        <li class="new">Mới</li>
                    </ul>
                    <div class="product-decs">
                        <a class="inner-link" href="shop-4-column.html"><span>${arrival.category}</span></a>
                        <h2><a href="${arrival.link}" class="product-link">${arrival.name}</a></h2>
                        <div class="rating-product">
                            ${'<i class="ion-android-star"></i>'.repeat(arrival.rating)}
                        </div>
                        <div class="pricing-meta">
                            <ul>
                                <li class="old-price">${arrival.priceOld}</li>
                                <li class="current-price">${arrival.priceNew}</li>
                                <li class="discount-price">${arrival.discount}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="add-to-link">
                        <ul>
                            <li class="cart"><a class="cart-btn" href="#">Xem chi tiết</a></li>
                            <li><a href="wishlist.html"><i class="ion-android-favorite-outline"></i></a></li>
                            <li><a href="compare.html"><i class="ion-ios-shuffle-strong"></i></a></li>
                        </ul>
                    </div>
                </article>
            </div>
        `;
        newArrivalsContainer.innerHTML += arrivalHTML;
    });
}
renderHotDeals(hotDeals);
renderNewArrivals(newArrivals);
function fetchGetHotDeal(){
   fetch('/api/product/hotdeal',{
    method: 'GET',
    headers: {
        'Content-Type': 'application/json',
    }
   })
}
</script>
