@extends('backend/layouts/app-admin')

@section('main')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <main class="app-content">
        <div class="app-title">
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item active"><a href="#"><b>Danh sách danh mục</b></a></li>
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
                                    data-target="#ModalAddCategory" type="button" title="Thêm danh mục">
                                    <i class="fas fa-plus"></i> Thêm danh mục
                                </a>

                                <a href="{{ route('cache.categories') }}" class="btn btn-primary">
                                    <i class="fas fa-sync"></i> Cache
                                </a>

                            </div>
                        </div>
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                                <tr>
                                    <th width="10"><input type="checkbox" id="selectAll"></th>
                                    <th>ID danh mục</th>
                                    <th>Tên danh mục</th>
                                    <th>Mô tả</th>
                                    <th>Hình ảnh</th>
                                    <th>Chức năng</th>
                                </tr>
                            </thead>
                            <tbody id="body-data-categories">
                            </tbody>
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
                                <h5>Chỉnh sửa thông tin danh mục</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label">ID</label>
                                <input class="form-control" type="text" id="editCategoryId" value="" required
                                    readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Tên danh mục</label>
                                <input class="form-control" type="text" id="editCategoryName" value="">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Mô tả</label>
                                <input class="form-control" type="text" id="editCategoryDescription" value="">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Hình ảnh</label>
                                <input class="form-control" type="file" id="editCategoryImage" accept="image/*">
                                <img id="editUserImagePreview" src="" alt="User Image" width="100px">
                            </div>
                            <button class="btn btn-save" type="button" id="save-edit-category">Lưu lại</button>
                            <a class="btn btn-cancel" onclick="closeModal()" href="#">Hủy bỏ</a>
                        </div>
                    </div>
                </div>
            </div>
    </main>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        getData();
        document.getElementById('save-edit-category').addEventListener('click', updateCategory);
    });

    function displayCategories(categories) {
        const dataForm = document.getElementById('body-data-categories');
        dataForm.innerHTML = '';

        categories.forEach(category => {
            const html = `
                <tr>
                    <td><input type="checkbox" name="check${category.id}" value="${category.id}" class="category-checkbox"></td>
                    <td>${category.id}</td>
                    <td>${category.name}</td>
                    <td>${category.description}</td>
           <td><img src="{{ asset('${category.image_path}') }}" alt="Category Image" style="max-width: 100px; max-height: 100px;"></td>


                    <td>
                        <button class="btn btn-primary btn-sm trash" type="button" title="Xóa" onclick="deleteCategory(${category.id})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        <button class="btn btn-primary btn-sm edit" type="button" title="Sửa" onclick="editCategory(${category.id})" data-toggle="modal" data-target="#ModalUP">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
            `;
            dataForm.innerHTML += html;
        });
    }

    function getData() {
        fetch('/admin/categories-index')
            .then(response => response.json())
            .then(data => displayCategories(data))
            .catch(error => console.error("Lỗi khi lấy dữ liệu:", error));
    }

    function openModal() {
        clearData();
        $('#ModalUP').modal('show');
    }

    function closeModal() {
        $('#ModalUP').modal('hide');
        clearData();
    }

    function clearData() {
        document.getElementById('editCategoryId').value = '';
        document.getElementById('editCategoryName').value = '';
        document.getElementById('editCategoryDescription').value = '';
        document.getElementById('editCategoryImage').value = '';
    }

    function editCategory(categoryId) {
        fetch(`/admin/categories-single?id=${categoryId}`)
            .then(response => response.json())
            .then(data => {
                const category = data[0];
                document.getElementById('editCategoryId').value = category.id;
                document.getElementById('editCategoryName').value = category.name;
                document.getElementById('editCategoryDescription').value = category.description;
                document.getElementById('editCategoryImage').value = category.image_path;
            })
            .catch(error => console.error("Lỗi khi lấy thông tin danh mục:", error));
    }

    function deleteCategory(categoryId) {
        if (!confirm("Bạn có chắc chắn muốn xóa danh mục này?")) {
            return;
        }

        fetch(`/admin/categories-remove?id=${categoryId}`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (response.ok) {
                    alert("Xóa danh mục thành công!");
                    getData();
                } else {
                    alert("Xóa danh mục thất bại.");
                }
            })
            .catch(error => console.error("Lỗi khi xóa danh mục:", error));
    }

    function updateCategory() {
        const id = document.getElementById('editCategoryId').value;
        const name = document.getElementById('editCategoryName').value;
        const description = document.getElementById('editCategoryDescription').value;
        const image = document.getElementById('editCategoryImage').files[0];
        const formData = new FormData();
        formData.append('id', id);
        formData.append('name', name);
        formData.append('description', description);
        formData.append('image_path',image);
        fetch(`/admin/categories-update`, {
                method: 'POST',
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (response.ok) {
                    alert("Cập nhật danh mục thành công!");
                    closeModal();
                    getData();
                } else {
                    alert("Cập nhật danh mục thất bại.");
                }
            })
            .catch(error => console.error("Lỗi khi cập nhật danh mục:", error));
    }
</script>
