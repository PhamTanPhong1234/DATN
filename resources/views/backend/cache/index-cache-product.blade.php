@extends('backend/layouts/app-admin')

@section('main')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <main class="app-content">
        <div class="app-title">
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item active"><a href="#"><b>Danh sách cache sản phẩm </b></a></li>
            </ul>
            <div id="clock"></div>
        </div>

        <script src="{{ asset('js/app.js') }}"></script>

        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                                <tr>
                                    <th width="10"><input type="checkbox" id="selectAll"></th>
                                    <th>ID</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Ảnh</th>
                                    <th>Danh mục</th>
                                    <th>Nhà sản xuất</th>
                                    <th>Trạng thái</th>
                                    <th>Chức năng</th>
                                </tr>
                            </thead>
                            <tbody id="body-data-products"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        getData();
        document.getElementById('save-edit-product').addEventListener('click', updateProduct);
    });

    function fetchData(url) {
        return fetch(url)
            .then(response => response.json())
            .catch(error => {
                console.error(`Lỗi khi lấy dữ liệu từ ${url}:`, error);
                return [];
            });
    }

    function displayProducts(products) {
        const dataForm = document.getElementById('body-data-products');
        if (products.length === 0) {
            dataForm.innerHTML = '<tr><td colspan="9">Không có sản phẩm nào</td></tr>';
            return;
        }

        dataForm.innerHTML = products.map(product => `
        <tr>
            <td><input type="checkbox" class="product-checkbox" value="${product.id}"></td>
            <td>${product.id}</td>
            <td>${product.name}</td>
            <td>${product.price}</td>
            <td><img src="{{ asset('${product.image_path}') }}" alt="Category Image" style="max-width: 100px; max-height: 100px;"></td>
            <td>${product.categories ? product.categories : 'Chưa có thể loại'}</td>
            <td>${product.artist ? product.artist : 'Chưa có nghệ sĩ'}</td>
            <td>${product.is_active ? 'Hoạt động' : 'Không hoạt động'}</td>
          <td>
    <!-- Restore Button -->
    <button class="btn btn-success btn-sm restore" onclick="restoreProduct(${product.id})">
        <i class="fas fa-undo"></i> Khôi phục
    </button>

    <!-- Permanent Delete Button -->
    <button class="btn btn-danger btn-sm restore" onclick="hardDeleteProduct(${product.id})">
        <i class="fas fa-trash-alt"></i> Xóa vĩnh viễn
    </button>
</td>

        </tr>
        `).join('');
    }

    function getData() {
        fetchData('/admin/product-index-cache')
            .then(data => displayProducts(data))
            .catch(error => console.error("Lỗi khi lấy dữ liệu:", error));
    }

    function hardDeleteProduct(productId) {
        if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này?")) {
            fetch(`/api/products/delete/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            })
            .then(() => {
                alert("Xóa sản phẩm thành công!");
                getData();
            })
            .catch(error => console.error("Lỗi khi xóa sản phẩm:", error));
        }
    }

    function restoreProduct(productId) {
        fetch(`/admin/restore-product?id=${productId}`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            }
        })
        .then(() => {
            alert("Khôi phục sản phẩm thành công!");
            getData();
        })
        .catch(error => console.error("Lỗi khi khôi phục sản phẩm:", error));
    }
</script>
