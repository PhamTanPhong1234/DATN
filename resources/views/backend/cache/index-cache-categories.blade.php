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
                       
                          <button class="btn btn-success btn-sm restore" type="button" title="Khôi phục" onclick="restoreCategory(${category.id})">
                                <i class="fas fa-undo"></i>
                            </button>
                       
                    </td>
                </tr>
            `;
            dataForm.innerHTML += html;
        });
    }

    function getData() {
        fetch('/admin/categories-index-cache')
            .then(response => response.json())
            .then(data => displayCategories(data))
            .catch(error => console.error("Lỗi khi lấy dữ liệu:", error));
    }

    function restoreCategory(categoryId) {
        if (!confirm("Bạn có chắc chắn muốn khôi phục danh mục này?")) {
            return;
        }

        fetch(`/admin/categories-restore?id=${categoryId}`, {
            method: "PATCH",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (response.ok) {
                alert("Khôi phục danh mục thành công!");
                getData();
            } else {
                alert("Khôi phục danh mục thất bại.");
            }
        })
        .catch(error => console.error("Lỗi khi khôi phục danh mục:", error));
    }

    function hardDeleteCategory(categoryId) {
        if (!confirm("Bạn có chắc chắn muốn xóa cứng danh mục này?")) {
            return;
        }

        fetch(`/admin/categories-hard-delete?id=${categoryId}`, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (response.ok) {
                alert("Xóa cứng danh mục thành công!");
                getData();
            } else {
                alert("Xóa cứng danh mục thất bại.");
            }
        })
        .catch(error => console.error("Lỗi khi xóa cứng danh mục:", error));
    }
</script>
