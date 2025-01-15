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
    <link rel="stylesheet" href="{{asset('assets/css/vendor/vendor.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/plugins/plugins.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/style.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/responsive.min.css')}}">
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
            <p id="product-count">Có <span id="productCount"></span> Sản Phẩm.</p>
        </div>
        <div class="select-shoing-wrap">
            <div class="shot-product">
                <p>Sắp xếp theo:</p>
            </div>
            <div class="shop-select">
                <select class="select_option" id="filter-select">
                    <option value="">Sắp xếp theo mới nhất</option>
                    <option value="az">Từ A đến Z</option>
                    <option value="za">Từ Z đến A</option>
                    <option value="instock">Còn hàng</option>
                </select>
            </div>
        </div>
    </div>

    <div class="container product-container">
        <div class="row">
            <!-- Form lọc sản phẩm -->
            <div class="col-md-3">
                <form id="filter-form" action="{{ route('products.filter') }}" method="POST" class="filter-form">
                    @csrf
                    <h5>Bộ lọc sản phẩm</h5>
                    <div class="filter-section w-25">
                        <label for="category">Chọn danh mục:</label>

                        <select name="category" id="category-list" style="display: block;">
                        </select>
                    </div>
                    <div class="filter-section w-25">
                        <label for="artist">Nghệ sĩ:</label>

                        <select name="artist" id="artist-list" style="display: block;">
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
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <button type="submit" class="btn btn-primary w-100 mt-5">Lọc</button>
                </form>
            </div>

            <div class="col-md-9">
                <div id="shop-1" class="tab-pane active">
                    <div class="row product-data" id="product-list">
                        @if (isset($products))
                        @foreach ($products as $product)
                        <div class="col-xl-3 col-md-6 col-lg-4 col-sm-6 col-xs-12">
                            <article class="list-product">
                                <div class="img-block">
                                    <a href="{{ route('single-product', ['id' => $product->id]) }}" class="thumbnail">
                                        <img class="first-img" src="{{ asset($product->images->isNotEmpty() ? $product->images->first()->image_path : null) }}" alt="{{ $product->name }}">
                                        <img class="second-img" src="{{ asset($product->images->isNotEmpty() ? $product->images->first()->image_path : null) }}" alt="{{ $product->name }}">
                                    </a>
                                    <div class="quick-view">
                                        <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            <i class="ion-ios-search-strong"></i>
                                        </a>
                                    </div>
                                </div>
                                <ul class="product-flag">
                                    <li class="new">{{ $product->isNew ? 'New' : 'Hot' }}</li>
                                </ul>
                                <div class="product-decs">
                                    <span>{{ $product->Category->name ? $product->Category->name : 'Chưa phân loại' }}</span>
                                    <h2><a class="product-link">{{ $product->name }}</a></h2>

                                    <div class="pricing-meta">
                                        <ul>
                                            <li class="old-price not-cut text-danger">{{ number_format($product->price) }} VND</li>
                                        </ul>
                                    </div>
                                    <div class="rating-product">
                                        Số lượng sản phẩm: {{ $product->stock}}
                                    </div>
                                </div>
                                <div class="add-to-link">
                                    <ul>
                                        <li class="cart"><a class="cart-btn" href="{{ route('single-product', ['id' => $product->id]) }}">Xem Sản Phẩm</a></li>
                                    </ul>
                                </div>
                            </article>
                        </div>
                        @endforeach
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hộp thoại thông báo -->

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"></script>
    <script>
        // đếm

        document.addEventListener("DOMContentLoaded", function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const updateProductCount = (count) => {
                const productCountElement = document.getElementById("product-count");
                productCountElement.textContent = `Có ${count} Sản Phẩm.`;
            };

            const displayCategories = (categories) => {
                console.log(categories); 
                const categoriesList = document.getElementById("category-list");
                categoriesList.innerHTML = ''; 

                const allOption = document.createElement('option');
                allOption.value = 'all';
                allOption.textContent = 'Tất cả';
                categoriesList.appendChild(allOption);

                categories.forEach(category => {
                    console.log(category); 
                    const categoryOption = document.createElement('option');
                    categoryOption.value = category.id;
                    categoryOption.textContent = category.name;
                    categoriesList.appendChild(categoryOption);
                });

                $('#category-list').niceSelect('update');
            };
            const displayArtist = (artists) => {
                console.log(artists); 
                const artistsList = document.getElementById("artist-list");
                artistsList.innerHTML = ''; 

                console.log(artists); 
                const allOption = document.createElement('option');
                allOption.value = 'all';
                allOption.textContent = 'Tất cả';
                artistsList.appendChild(allOption);

                artists.forEach(artist => {
                    const artistOption = document.createElement('option');
                    artistOption.value = artist.id;
                    artistOption.textContent = artist.name;
                    artistsList.appendChild(artistOption);
                });
                $('#artist-list').niceSelect('update');
            };

            const selectOptions = document.querySelectorAll('.nice-select .option');
            selectOptions.forEach(option => {
                option.addEventListener("click", (event) => {
                    const filter = event.target.getAttribute('data-value');
                    fetchApiProduct(filter);
                });
            });

            // Hàm gọi API để lấy sản phẩm

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
            const fetchApiArtist = (filter = "") => {
                axios.get('http://127.0.0.1:8000/api/artist', {
                        params: {
                            filter
                        },
                    })
                    .then(response => {
                        console.log(response.data);
                        const data = response.data;
                        displayArtist(data.artistData);
                    })
                    .catch(error => {
                        console.error("Lỗi:", error);
                    });
            };
            $('select').niceSelect();
            fetchApiArtist();
            fetchApiCategories();
            window.onload = function() {
                var productElements = document.querySelectorAll('.col-xl-3.col-md-6.col-lg-4.col-sm-6.col-xs-12');
                var productCount = productElements.length;
                document.getElementById('productCount').innerText = productCount;
            }

        });
    </script>
</body>

</html>