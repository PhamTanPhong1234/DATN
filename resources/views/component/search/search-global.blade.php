<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        .dropdown_search_list {
            max-height: 300px;
            overflow-y: auto;
            padding: 0;
            margin: 0;
            list-style: none;
            border: 1px solid #ccc;
            background-color: white;
            position: absolute;
            width: 100%;
            z-index: 1000;
        }

        .product-suggestion {
            display: flex;
            align-items: center;
            padding: 10px;
            cursor: pointer;
        }

        .product-suggestion:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
    <div class="header_account_list search_list">
        <a href="javascript:void(0)"><i class="ion-ios-search-strong"></i></a>
        <div class="dropdown_search">
            <form action="{{ url('search-result') }}" method="GET">
                <input type="text" name="query" id="search_product" placeholder="Tìm kiếm trong cửa hàng...">
                <button type="submit"><i class="ion-ios-search-strong"></i></button>
            </form>
            <ul id="suggestions" class="dropdown_search_list" style="display: none;"></ul>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search_product');
            const suggestionsList = document.getElementById('suggestions');
            searchInput.addEventListener('input', async function(event) {
                const query = event.target.value;
                if (query.length < 3) {
                    clearSuggestions();
                    return;
                }
                try {
                    const response = await axios.get('{{ route('search.product') }}', {
                        params: {
                            query
                        }
                    });
                    const products = response.data;
                    console.log("API response received:", products);
                    displaySuggestions(products);
                } catch (error) {
                    console.error('Error fetching search results:', error);
                    clearSuggestions();
                }
            });

            document.addEventListener('click', function(event) {
                if (!event.target.closest('.dropdown_search')) {
                    clearSuggestions();
                }
            });

            function displaySuggestions(products) {
                suggestionsList.innerHTML = '';
                if (products.length === 0) {
                    const noResultsItem = document.createElement('li');
                    noResultsItem.textContent = 'Không tìm thấy sản phẩm';
                    suggestionsList.appendChild(noResultsItem);
                    suggestionsList.style.display = 'block';
                    return;
                }
                products.forEach(product => {
                    const listItem = document.createElement('li');
                    listItem.classList.add('product-suggestion');
                    listItem.innerHTML = `
                      <div class="d-flex align-items-center">
    <a href="single-product?id=${product.id}" class="d-flex align-items-center w-100 text-decoration-none">
        <img src="${product.images.length > 0 ? product.images[0].image_path : ''}" alt="${product.name}" class="mr-3" style="width: 40px; height: 50px; object-fit: cover;">
        <div>
            <div>${product.name}</div>
            <div>${product.price ? product.price : 'Liên hệ'}</div>
        </div>
        <span class="ml-auto">Xem chi tiết</span>
    </a>
</div>
                    `;

                    listItem.addEventListener('click', function() {
                        searchInput.value = product.name;
                        clearSuggestions();
                    });

                    suggestionsList.appendChild(listItem);
                });

                suggestionsList.style.display = 'block';
            }
            function clearSuggestions() {
                suggestionsList.innerHTML = '';
                suggestionsList.style.display = 'none';
            }
        });
    </script>
</body>

</html>
