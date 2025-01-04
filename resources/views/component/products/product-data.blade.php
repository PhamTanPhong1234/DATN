<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ecolife - Mẫu eCommerce Đa Năng HTML</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon/favicon.png">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800&family=DM+Serif+Display:ital@0;1&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/vendor/vendor.min.css">
    <link rel="stylesheet" href="assets/css/plugins/plugins.min.css">
    <link rel="stylesheet" href="assets/css/style.min.css">
    <link rel="stylesheet" href="assets/css/responsive.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .filter-section {
            margin-bottom: 15px;
        }

        .product-container {
            margin-top: 20px;
        }

        .filter-form {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <div class="shop-top-bar">
        <div class="shop-tab nav mb-res-sm-15">
            <a class="active" href="#shop-1" data-bs-toggle="tab">
                <i class="fa fa-th show_grid"></i>
            </a>
            <p id="product-count">Có 0 Sản Phẩm.</p>
        </div>
        <div class="select-shoing-wrap">
            <div class="shot-product">
                <p>Sắp xếp theo:</p>
            </div>
            <div class="shop-select">
                <select class="select_option" id="filter-select" style="display: none;">
                    <option value="">Sắp xếp theo mới nhất</option>
                    <option value="az">Từ A đến Z</option>
                    <option value="za">Từ Z đến A</option>
                    <option value="instock">Còn hàng</option>
                </select>
                <div class="nice-select select_option" tabindex="0">
                    <span class="current">Từ A đến Z</span>
                    <ul class="list">
                        <li data-value="" class="option">Sắp xếp theo mới nhất</li>
                        <li data-value="az" class="option selected">Từ A đến Z</li>
                        <li data-value="za" class="option">Từ Z đến A</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container product-container">
        <div class="row">
            <!-- Form lọc sản phẩm -->
            <div class="col-md-3">
                <form id="filter-form" method="GET" action="{{ route('products.filter') }}" class="filter-form">
                    @csrf
                    <h5>Bộ lọc sản phẩm</h5>
                    <div class="filter-section">
                        <label for="category">Chọn danh mục:</label><br>
                        <!-- <select name="categories-list" id="category-list"  ></select> -->
                        <select name="category" id="category-list" style="display: block;">
                            <!-- <option value="">Tất cả</option> -->
                        </select>
                    </div>

                    <div class="filter-section">
                        <br><br>
                        <label for="price_min">Giá từ:</label>
                        <br>
                        <input type="number" name="price_min" id="price_min" value="{{ request('price_min') }}" class="form-control" placeholder="Min giá">
                    </div>

                    <div class="filter-section">
                        <label for="price_max">Giá đến:</label>
                        <input type="number" name="price_max" id="price_max" value="{{ request('price_max') }}" class="form-control" placeholder="Max giá">
                    </div>

                    <div class="filter-section">
                        <label for="quantity_min">Số lượng từ:</label>
                        <input type="number" name="quantity_min" id="quantity_min" value="{{ request('quantity_min') }}" class="form-control" placeholder="Min số lượng">
                    </div>

                    <div class="filter-section">
                        <label for="quantity_max">Số lượng đến:</label>
                        <input type="number" name="quantity_max" id="quantity_max" value="{{ request('quantity_max') }}" class="form-control" placeholder="Max số lượng">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-5">Lọc</button>
                </form>
            </div>

            <!-- Danh sách sản phẩm -->
            <div class="col-md-9">
                <div id="shop-1" class="tab-pane active">
                    <div class="row product-data" id="product-list">
                        <!-- Sản phẩm sẽ được hiển thị ở đây -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const updateProductCount = (count) => {
                const productCountElement = document.getElementById("product-count");
                productCountElement.textContent = `Có ${count} Sản Phẩm.`;
            };
            const displayCategories = (categories) => {
                console.log(categories); // Kiểm tra dữ liệu categories
                const categoriesList = document.getElementById("category-list");
                categoriesList.innerHTML = ''; // Xóa danh sách cũ trước khi thêm mới

                // Thêm một option "Tất cả" vào đầu danh sách
                const allOption = document.createElement('option');
                allOption.value = '';
                allOption.textContent = 'Tất cả';
                categoriesList.appendChild(allOption);

                // Thêm các danh mục vào select
                categories.forEach(category => {
                    console.log(category); // Kiểm tra từng danh mục
                    const categoryOption = document.createElement('option');
                    categoryOption.value = category.id;
                    categoryOption.textContent = category.name;
                    categoriesList.appendChild(categoryOption);
                });
            };

            // Hàm hiển thị các sản phẩm
            const displayProducts = (products) => {
                const productList = document.getElementById("product-list");
                productList.innerHTML = ""; // Xóa danh sách sản phẩm cũ
                products.forEach(product => {
                    const productHTML = `
                <div class="col-xl-3 col-md-6 col-lg-4 col-sm-6 col-xs-12">
                    <article class="list-product">
                        <div class="img-block">
                            <a href="single-product?id=${product.id}" class="thumbnail">
                                <img class="first-img" src="${product.image ? product.image : 'default/image'}" alt="${product.name}">
                                <img class="second-img" src="${product.image}" alt="${product.name}">
                            </a>
                            <div class="quick-view">
                                <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <i class="ion-ios-search-strong"></i>
                                </a>
                            </div>
                        </div>
                        <ul class="product-flag">
                            <li class="new">${product.isNew ? 'New' : ''}</li>
                        </ul>
                        <div class="product-decs">
                            <span>${product.category_name ? product.category_name : 'Chưa phân loại'}</span>
                            <h2><a class="product-link">${product.name}</a></h2>
                            <div class="rating-product">
                                ${generateStars(product.rating)}
                            </div>
                            <div class="pricing-meta">
                                <ul>
                                    <li class="old-price not-cut text-danger">${product.price} VND</li>
                                </ul>
                            </div>
                        </div>
                        <div class="add-to-link">
                            <ul>
                                <li class="cart"><a class="cart-btn" href="single-product?id=${product.id}">Xem Sản Phẩm</a></li>
                            </ul>
                        </div>
                    </article>
                </div>
            `;
                    productList.innerHTML += productHTML;
                });
            };

            // Hàm sinh sao đánh giá cho sản phẩm
            const generateStars = (rating) => {
                let stars = '';
                for (let i = 0; i < 5; i++) {
                    stars += i < rating ? '<i class="ion-android-star"></i>' :
                        '<i class="ion-android-star-outline"></i>';
                }
                return stars;
            };

            // Lắng nghe sự kiện thay đổi bộ lọc sản phẩm
            const selectOptions = document.querySelectorAll('.nice-select .option');
            selectOptions.forEach(option => {
                option.addEventListener("click", (event) => {
                    const filter = event.target.getAttribute('data-value');
                    fetchApiProduct(filter);
                });
            });

            // Hàm gọi API để lấy sản phẩm
            const fetchApiProduct = (filter = "") => {
                axios.get('http://127.0.0.1:8000/api/products', {
                        params: {
                            filter
                        }
                    })
                    .then(response => {
                        const data = response.data;
                        displayProducts(data.productData);
                        updateProductCount(data.productData.length);
                    })
                    .catch(error => {
                        console.error("Lỗi:", error);
                    });
            };
            const fetchApiCategories = (filter = "") => {
                axios.get('http://127.0.0.1:8000/api/categories', {
                        params: {
                            filter
                        },
                    })
                    .then(response => {
                        console.log(response.data);
                        const data = response.data;
                        displayCategories(data.categoriesData);
                    })
                    .catch(error => {
                        console.error("Lỗi:", error);
                    });
            };
            fetchApiProduct();
            fetchApiCategories();
        });
    </script>