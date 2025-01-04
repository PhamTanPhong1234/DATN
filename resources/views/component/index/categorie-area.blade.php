<section class="categorie-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- Tiêu đề phần -->
                <div class="section-title mt-res-sx-30px mt-res-md-30px">
                    <h2>Danh Mục Phổ Biến</h2>
                    <p>Thêm các danh mục phổ biến vào danh sách hàng tuần</p>
                </div>
                <!-- Tiêu đề phần -->
            </div>
        </div>
        <!-- Bắt đầu Slider Danh Mục -->
        <div class="category-slider owl-carousel owl-nav-style" id="category-slider">
            <!-- Nội dung sẽ được thêm vào đây -->
        </div>
        <!-- Kết thúc Slider Danh Mục -->
    </div>
</section>

<script>
    const containerCategorySlider = document.getElementById('category-slider');
    const categoryUrl = "{{ url('categories') }}";  // Base URL for categories page

    // Hàm hiển thị danh mục
    const displayCategories = (categories) => {
        containerCategorySlider.innerHTML = ''; // Xóa nội dung cũ (nếu có)
        categories.forEach(category => {
            const categoryHTML = `
                <div class="category-item">
                    <div class="category-list mb-30px">
                        <div class="category-thumb">
                            <a href="${categoryUrl}?id=${category.id}">
                                <img src="${category.image}" alt="${category.name}" />
                            </a>
                        </div>  
                        <div class="desc-listcategoreis">
                            <div class="name_categories" style="color:#fff;">
                                <h4 style="color:#fff;">${category.name}</h4>
                            </div>
                            <span style="color:#fff;" class="number_product">${category.productCount} Sản Phẩm</span>
                            <a style="color:#fff;" href="${categoryUrl}?id=${category.id}"> Mua Ngay <i class="ion-android-arrow-dropright-circle"></i></a>
                        </div>
                    </div>
                </div>
            `;
            containerCategorySlider.innerHTML += categoryHTML;
        });

        // Initialize or refresh the OwlCarousel after adding content
        $('.category-slider').owlCarousel('destroy');
        $('.category-slider').owlCarousel({
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

    function fetchCategorie_Hot() {
        fetch('/api/categories/hot', {
                method: "GET",
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                displayCategories(data.categoriesData);
            })
            .catch(error => {
                console.error('Error fetching hot categories:', error);
            });
    }
    fetchCategorie_Hot();
</script>
