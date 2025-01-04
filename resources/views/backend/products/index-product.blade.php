@extends('backend/layouts/app-admin')

@section('main')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
    </head>

    <main class="app-content">
        <div class="app-title">
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item active"><a href="#"><b>Danh sách sản phẩm</b></a></li>
            </ul>
            <div id="clock"></div>
        </div>

        <script src="{{ asset('js/app.js') }}"></script>

        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                        <div class="row element-button">
                            <div class="col-sm-2">
                                <a class="btn btn-success btn-sm" onclick="openModal()" data-toggle="modal"
                                    data-target="#ModalUP" type="button">
                                    <i class="fas fa-plus"></i> Thêm sản phẩm
                                </a>
                                <a href="{{ route('cache.product') }}" class="btn btn-primary">
                                    <i class="fas fa-sync"></i> Cache
                                </a>
                            </div>
                        </div>
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

        <!-- Modal -->
        <div class="modal fade" id="ModalUP" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"
            data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <h5>Chỉnh sửa thông tin sản phẩm</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label">ID</label>
                                <input class="form-control" type="text" id="editProductId" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Tên sản phẩm</label>
                                <input class="form-control" type="text" id="editProductName">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Giá</label>
                                <input class="form-control" type="number" id="editProductPrice">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Số lượng</label>
                                <input class="form-control" type="number" id="editProductCount" min="1" value="1">
                            </div>
                            
                            <div class="form-group col-md-12">
                                <label class="control-label">Mô tả</label>
                                <textarea class="form-control" id="editProductDescription"></textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Tải lên MP3</label>
                                <input class="form-control" type="file" id="editProductAudio" accept="audio/mp3">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Ảnh</label>
                                <input class="form-control" type="file" id="editProductImage" accept="image/*">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Trạng thái</label>
                                <select class="form-control" id="editProductStatus">
                                    <option value="1">Hoạt động</option>
                                    <option value="0">Không hoạt động</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Danh mục</label>
                                <select class="form-control" id="editProductCategories"></select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Nhà sản xuất</label>
                                <select class="form-control" id="editProductArtist"></select>
                            </div>
                        </div>
                        <button class="btn btn-save" type="button" id="save-edit-product">Lưu lại</button>
                        <a class="btn btn-cancel" onclick="closeModal()">Hủy bỏ</a>
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

    function updateSelectOptions(selectElement, data, currentValue, defaultText) {
        selectElement.innerHTML = `<option value="">${defaultText}</option>`;
        data.forEach(item => {
            const option =
                `<option value="${item.id}" ${item.id === currentValue ? 'selected' : ''}>${item.name}</option>`;
            selectElement.innerHTML += option;
        });
    }

    function getCategories(current) {
        fetchData('/api/categories')
            .then(data => {
                const categoriesSelect = document.getElementById('editProductCategories');
                updateSelectOptions(categoriesSelect, data.categoriesData, current.categories_id, "Chọn danh mục");
            });
    }

    function getArtist(current) {
        fetchData('/api/artist')
            .then(data => {
                const artistSelect = document.getElementById('editProductArtist');
                updateSelectOptions(artistSelect, data, current.artist_id, "Chọn nhà sản xuất");
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
                <button class="btn btn-primary btn-sm trash" onclick="deleteProduct(${product.id})">
                    <i class="fas fa-trash-alt"></i>
                </button>
                <button class="btn btn-primary btn-sm edit" onclick="editProduct(${product.id})" data-toggle="modal" data-target="#ModalUP">
                    <i class="fas fa-edit"></i>
                </button>
            </td>
        </tr>
    `).join('');
    }

    function getData() {
        fetchData('/admin/product-index')
            .then(data => displayProducts(data))
            .catch(error => console.error("Lỗi khi lấy dữ liệu:", error));
    }

    function editProduct(productId) {
        fetchData(`/admin/product-single?id=${productId}`)
            .then(data => {
                const {
                    id,
                    name,
                    price,
                    count,
                    description,
                    status,
                    categories_id,
                    artist_id
                } = data;
                document.getElementById('editProductId').value = id;
                document.getElementById('editProductName').value = name;
                document.getElementById('editProductPrice').value = price;
                document.getElementById('editProductCount').value = count;
                document.getElementById('editProductDescription').value = description;
                document.getElementById('editProductStatus').value = status;
              

                getCategories({
                    categories_id
                });
                getArtist({
                    artist_id
                });
            })
            .catch(error => console.error("Lỗi khi lấy thông tin sản phẩm:", error));
    }

    function updateProduct() {
        const formData = new FormData();
        const fields = ['editProductId', 'editProductName', 'editProductPrice', 'editProductCount',
            'editProductDescription', 'editProductStatus', 'editProductCategories', 'editProductArtist'
        ];

        fields.forEach(field => formData.append(field.replace('editProduct', '').toLowerCase(), document.getElementById(
            field).value));

        const image = document.getElementById('editProductImage').files[0];
        if (image) formData.append('image', image);

        const mp3 = document.getElementById('editProductAudio').files[0]; 
if (mp3) { 
    formData.append('mp3', mp3); 
}

        const id = document.getElementById('editProductId').value;

        fetch(`/admin/update-product?id=${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData
            })
            .then(response => response.json())
            .then(() => {
                alert('Cập nhật thành công');
                closeModal();
                getData();
            })
            .catch(error => console.error("Lỗi khi cập nhật sản phẩm:", error));
    }

    function deleteProduct(productId) {
        if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này?")) {
            fetch(`/admin/delete-product?id=${productId}`, {
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

    function openModal() {
        Promise.all([fetchData('/api/categories'), fetchData('/api/artist')])
            .then(([categories, artists]) => {
                updateSelectOptions(document.getElementById('editProductCategories'), categories.categoriesData, '',
                    "Chọn danh mục");
                updateSelectOptions(document.getElementById('editProductArtist'), artists, '', "Chọn nhà sản xuất");
            })
            .catch(error => console.error("Lỗi khi lấy dữ liệu:", error));

        $('#ModalUP').modal('show');
    }

    function closeModal() {
        $('#ModalUP').modal('hide');
        document.getElementById('editProductId').value = '';
        document.getElementById('editProductName').value = '';
        document.getElementById('editProductPrice').value = '';
        document.getElementById('editProductCount').value = '';
        document.getElementById('editProductDescription').value = '';
        document.getElementById('editProductImage').value = '';
        document.getElementById('editProductStatus').value = '1';
        document.getElementById('editProductCategories').selectedIndex = 0;
        document.getElementById('editProductArtist').selectedIndex = 0;
    }
</script>
