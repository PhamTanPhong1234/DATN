@extends('backend/layouts/app-admin')

@section('main')

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<main class="app-content">
    <div class="app-title">
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item active"><a href="#"><b>Danh sách tin tức</b></a></li>
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
                            <a class="btn btn-success btn-sm" onclick="openModal()" data-toggle="modal" data-target="#ModalAddCategory" type="button" title="Thêm tin tức">
                                <i class="fas fa-plus"></i> Thêm tin tức
                            </a>
                        </div>
                    </div>
                    <table class="table table-hover table-bordered" id="sampleTable">
                        <thead>
                            <tr>
                                <th width="10"><input type="checkbox" id="selectAll"></th>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Content</th>
                                <th>Author</th>
                                <th>Image</th>
                                <th>Status</th>
                                <th>Chức năng</th>
                                <th>Xem chi tiết</th>
                            </tr>
                        </thead>    
                        <tbody id="body-data-categories">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalUP" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <h5>Chỉnh sửa thông tin tin tức</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="control-label">ID</label>
                            <input class="form-control" type="text" id="editNewsId" value="" required readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Tên tin tức</label>
                            <input class="form-control" type="text" id="editNewsTitle" value="">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Nội dung</label>
                            <textarea class="form-control" id="editNewsContent"></textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Tác giả</label>
                            <input class="form-control" type="text" id="editNewsAuthor"  value="" required readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Ảnh</label>
                            <input class="form-control" type="file" id="editNewsImage" accept="image/*">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Trạng thái</label>
                            <select class="form-control" id="editNewsStatus">
                                <option value="1">Hoạt động</option>
                                <option value="0">Chưa hoạt động</option>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-save" type="button" id="save-edit-news">Lưu lại</button>
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
        document.getElementById('save-edit-news').addEventListener('click', updateCategory);
    });

    function displayCategories(categories) {
        const dataForm = document.getElementById('body-data-categories');
        dataForm.innerHTML = ''; 

        categories.forEach(category => {
            const html = `
                <tr>
                    <td><input type="checkbox" name="check${category.id}" value="${category.id}" class="category-checkbox"></td>
                    <td>${category.id}</td>
                    <td>${category.title}</td>
                    <td>${category.content}</td>
                  <td>${category.user && category.user.name ? category.user.name : "Không xác định"}</td>
                     <td><img src="{{ asset('${category.image_path}') }}" alt="Category Image" style="max-width: 100px; max-height: 100px;"></td>
                    <td>${category.status}</td>
                    <td>
                        <button class="btn btn-primary btn-sm trash" type="button" title="Xóa" onclick="deleteCategory(${category.id})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        <button class="btn btn-primary btn-sm edit" type="button" title="Sửa" onclick="editNews(${category.id})" data-toggle="modal" data-target="#ModalUP">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
               <td><a href="/admin/news-content/${category.id}">Chi tiết</a></td>

                </tr>
            `;
            dataForm.innerHTML += html; 
        });
    }

    function getData() {
        fetch('/admin/news-index')
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
        document.getElementById('editNewsId').value = ''; 
        document.getElementById('editNewsTitle').value = ''; 
        document.getElementById('editNewsContent').value = ''; 
        document.getElementById('editNewsAuthor').value = ''; 
        document.getElementById('editNewsImage').value = ''; 
        document.getElementById('editNewsStatus').value = ''; 
    }

    function editNews(newsId) {
        fetch(`/admin/news-single?id=${newsId}`)
        .then(response => response.json())
        .then(data => {
            const news = data[0];
            document.getElementById('editNewsId').value = news.id;
            document.getElementById('editNewsTitle').value = news.title;
            document.getElementById('editNewsContent').value = news.content;
            document.getElementById('editNewsAuthor').value = news.user.name;
            document.getElementById('editNewsImage').value = news.image_path;
            document.getElementById('editNewsStatus').value = news.status;
        })
        .catch(error => console.error("Lỗi khi lấy thông tin tin tức:", error));
    }

    function deleteCategory(categoryId) {
        if (!confirm("Bạn có chắc chắn muốn xóa danh mục này?")) {
            return;
        }

        fetch(`/admin/news-remove?id=${categoryId}`, {
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
        const id = document.getElementById('editNewsId').value;
        const title = document.getElementById('editNewsTitle').value;
        const content = document.getElementById('editNewsContent').value;
        const image = document.getElementById('editNewsImage').files[0];;
        const status = document.getElementById('editNewsStatus').value;

        const formData = new FormData();
        formData.append('id', id);
        formData.append('title', title);
        formData.append('content', content);
        formData.append('image', image);
        formData.append('status', status);

        fetch(`/admin/news-update`, {
            method: 'POST',
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (response.ok) {
                alert("Cập nhật tin tức thành công!");
                closeModal();
                getData();
            } else {
                alert("Cập nhật tin tức thất bại.");
            }
        })
        .catch(error => console.error("Lỗi khi cập nhật tin tức:", error));
    }
</script>
