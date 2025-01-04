@extends('backend/layouts/app-admin')

@section('main')

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<main class="app-content">
    <div class="app-title">
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item active"><a href="#"><b>Danh sách cache user</b></a></li>
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
                                <th>ID người dùng</th>
                                <th>Tên người dùng</th>
                                <th>Hình ảnh</th>
                                <th>Email</th>
                                <th>Trạng thái</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>    
                        <tbody id="body-data-users">
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
    });

    function displayUsers(users) {
        const dataForm = document.getElementById('body-data-users');
        if (!dataForm) {
            console.error('Phần tử #body-data-users không tồn tại.');
            return;
        }

        dataForm.innerHTML = ''; 

        users.forEach(user => {
            const html = `
                <tr>
                    <td width="10"><input type="checkbox" name="check${user.id}" value="${user.id}" class="user-checkbox"></td>
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td><img src="${user.avatar}" alt="User Image" width="100px"></td>
                    <td>${user.email}</td>
                    <td>${user.is_delete == 0 ? "Chưa xóa" : "Đã xóa"}</td>
                 <td>
    <!-- Restore Button -->
    <button class="btn btn-success btn-sm restore" type="button" title="Khôi phục" onclick="restoreUser(${user.id})">
        <i class="fas fa-undo"></i> Khôi phục
    </button>

    <!-- Permanent Delete Button -->
    <button class="btn btn-danger btn-sm restore" type="button" title="Xóa vĩnh viễn" onclick="permanentDeleteUser(${user.id})">
        <i class="fas fa-trash-alt"></i> Xóa vĩnh viễn
    </button>
</td>

                </tr>
            `;
            dataForm.innerHTML += html; 
        });
    }

    function getData() {
        fetch('/admin/get-user-cache', {
            method: "GET",
            headers: {
                "Content-Type": "application/json", 
                "Accept": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => displayUsers(data))
        .catch(error => console.error("Lỗi khi lấy dữ liệu:", error));
    }

    function restoreUser(userId) {
        if (!confirm("Bạn có chắc chắn muốn khôi phục người dùng này?")) {
            return;
        }
        fetch(`/api/users/restore/${userId}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (response.ok) {
                alert("Khôi phục người dùng thành công!");
                getData();
            } else {
                alert("Khôi phục người dùng thất bại.");
            }
        })
        .catch(error => console.error("Lỗi khi khôi phục người dùng:", error));
    }

    function permanentDeleteUser(userId) {
        if (!confirm("Bạn có chắc chắn muốn xóa người dùng này vĩnh viễn?")) {
            return;
        }

        fetch(`/api/users/delete/${userId}`, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (response.ok) {
                alert("Xóa người dùng thành công!");
                getData();
            } else {
                alert("Xóa người dùng thất bại.");
            }
        })
        .catch(error => console.error("Lỗi khi xóa người dùng:", error));
    }

</script>
