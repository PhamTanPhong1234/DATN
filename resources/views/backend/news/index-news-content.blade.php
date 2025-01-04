@extends('backend.layouts.app-admin')

@section('main')
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<main class="app-content">
    <div class="app-title">
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item active"><a href="#"><b>Danh sách nội dung</b></a></li>
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
                            <a class="btn btn-success btn-sm" onclick="openModal()" data-toggle="modal" data-target="#ModalUP" type="button" title="Thêm tin tức">
                                <i class="fas fa-plus"></i> Thêm tin tức
                            </a>
                        </div>
                    </div>
                    <div class="container mt-4 mb-2">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Thông tin bài viết</h5>
                                <p class="card-text">
                                    <span class="news-id d-block mb-2">
                                        <strong id="news_id" value="{{ $news['id'] }}"> {{ $news['id'] ?? "Không tồn tại" }}</strong>
                                    </span>
                                    <span class="news-title d-block">
                                        <strong>Title:</strong> {{ $news['title'] ?? "Không tồn tại" }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                   
                    <table class="table table-hover table-bordered" id="sampleTable">
                        <thead>
                            <tr>
                                <th width="10"><input type="checkbox" id="selectAll"></th>
                                <th>ID</th>
                                <th>Content</th>
                                <th>Image</th>
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

    <div class="modal fade" id="ModalUP" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <h5 id="modalTitle">Chỉnh sửa thông tin tin tức</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="control-label">ID</label>
                            <input class="form-control" type="text" id="editNewsId" value="" required readonly>
                        </div>
                    
                        <div class="form-group col-md-6">
                            <label class="control-label">Nội dung</label>
                            <textarea class="form-control" id="editNewsContent"></textarea>
                        </div>
                      
                        <div class="form-group col-md-6">
                            <label class="control-label">Ảnh</label>
                            <input class="form-control" type="file" id="editNewsImage" accept="image/*">
                        </div>
                    </div>
                    <button class="btn btn-save" type="button" id="save-news">Lưu lại</button>
                    <a class="btn btn-cancel" onclick="closeModal()" href="#">Hủy bỏ</a>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const newsId = document.getElementById('news_id').getAttribute('value');
        getData(newsId);
        document.getElementById('save-news').addEventListener('click', saveNews);
    });

    function displayCategories(categories) {
        const dataForm = document.getElementById('body-data-categories');
        dataForm.innerHTML = ''; 

        let htmlContent = '';
        categories.forEach(category => {
            htmlContent += `
                <tr>
                    <td><input type="checkbox" name="check${category.id}" value="${category.id}" class="category-checkbox"></td>
                    <td>${category.id}</td>
                    <td>${category.content}</td>
                    <td><img src="{{ asset('${category.image}') }}" alt="Category Image" style="max-width: 100px; max-height: 100px;"></td>
                    <td>
                        <button class="btn btn-primary btn-sm trash" type="button" title="Xóa" onclick="deleteCategory(${category.id})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        <button class="btn btn-primary btn-sm edit" type="button" title="Sửa" onclick="editNews(${category.id})" data-toggle="modal" data-target="#ModalUP">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        dataForm.innerHTML = htmlContent;
    }

    function getData(newsId) {
        fetch(`/admin/content-index?id=${newsId}`)
        .then(response => response.json())
        .then(data => displayCategories(data))
        .catch(error => console.error("Lỗi khi lấy dữ liệu:", error));
    }

    function openModal() {
        clearData();
        document.getElementById('modalTitle').innerText = 'Thêm tin tức';
        $('#ModalUP').modal('show');
    }

    function closeModal() {
        $('#ModalUP').modal('hide');
        clearData();
    }

    function clearData() {
        document.getElementById('editNewsId').value = ''; 
        document.getElementById('editNewsContent').value = ''; 
        document.getElementById('editNewsImage').value = ''; 
    }

    function editNews(newsId) {
        document.getElementById('modalTitle').innerText = 'Chỉnh sửa thông tin tin tức';
        fetch(`/admin/news-content-single?id=${newsId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editNewsId').value = data.id;
            document.getElementById('editNewsContent').value = data.content;
        })
        .catch(error => console.error("Lỗi khi lấy thông tin tin tức:", error));
        
        $('#ModalUP').modal('show');
    }

    function deleteCategory(categoryId) {
        if (!confirm("Bạn có chắc chắn muốn xóa danh mục này?")) {
            return;
        }

        fetch(`/admin/news-content-remove?id=${categoryId}`, {
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
                getData(document.getElementById('news_id').getAttribute('value'));
            } else {
                alert("Xóa danh mục thất bại.");
            }
        })
        .catch(error => console.error("Lỗi khi xóa danh mục:", error));
    }

    function saveNews() {
        const id = document.getElementById('editNewsId').value;
        const news_id = document.getElementById('news_id').getAttribute('value');
        const content = document.getElementById('editNewsContent').value.trim();
        const image = document.getElementById('editNewsImage').files[0];

        if (!content) {
            alert("Vui lòng nhập nội dung.");
            return;
        }

        const formData = new FormData();
        formData.append('id', id);
        formData.append('content', content);
        if (image) formData.append('image', image);
        formData.append('news_id', news_id);

        const url = id ? `/admin/news-content-update` : `/admin/news-content-create`;

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Lưu tin tức thành công!");
                closeModal();
                getData(news_id);
            } else {
                getData(news_id);
                closeModal();
                alert(data.message);
            }
        })
        .catch(error => {
            console.error("Lỗi khi lưu tin tức:", error);
            alert("Có lỗi xảy ra khi lưu tin tức.");
        });
    }
</script>
