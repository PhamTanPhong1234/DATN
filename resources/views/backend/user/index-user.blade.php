@extends('backend/layouts/app-admin')

@section('main')

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<main class="app-content">
    <div class="app-title">
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item active"><a href="#"><b>Danh sách người dùng</b></a></li>
        </ul>
        <div id="clock"></div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <a href="{{ route('cache.user') }}" class="btn btn-primary">
                        <i class="fas fa-sync"></i> Cache
                    </a>
                    
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

    <div class="modal fade" id="ModalUP" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <span class="thong-tin-thanh-toan">
                                <h5>Chỉnh sửa thông tin người dùng</h5>
                            </span> 

                        </div>
                        <div id="editUserError" class="text-danger w-100 text-center"></div>

                    </div>
                    <input class="form-control" type="text" hidden id="editUserId" value="" readonly>
                    <div class="row">

                        <div class="form-group col-md-12">
                            <label class="control-label">Tên người dùng</label>
                            <input class="form-control" type="text" id="editUserName" value="">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Email</label>
                            <input class="form-control" type="email" id="editUserEmail" value="" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Hình ảnh</label>
                            <input class="form-control" type="file" id="editUserImage" accept="image/*">
                            <img id="editUserImagePreview" src="" alt="User Image" width="100px">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Trạng thái</label>
                            <select class="form-control" id="editUserStatus">
                                <option value="1">Hoạt động</option>
                                <option value="0">Không hoạt động</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Quyền</label>
                            <select class="form-control" id="editUserRole">
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-save" type="button" id="save-edit-user" onclick="updateUser()">Lưu lại</button>
                    <a class="btn btn-cancel" data-dismiss="modal" href="#">Hủy bỏ</a>
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
                    <td>${user.is_active == 0 ? "Chưa kích hoạt":"Đã kích hoạt"}</td>
                    <td>
                        <button class="btn btn-primary btn-sm trash" type="button" title="Xóa" onclick="deleteUser(${user.id})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        <button class="btn btn-primary btn-sm edit" type="button" title="Sửa" onclick="editUser(${user.id})" data-toggle="modal" data-target="#ModalUP">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
            `;
            dataForm.innerHTML += html; 
        });
    }

    function getData() {
        fetch('/api/users', {
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

    
    


    function deleteUser(userId) {
        if (!confirm("Bạn có chắc chắn muốn xóa người dùng này?")) {
            return;
        }

        fetch(`/api/users/${userId}`, {
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

    function editUser(userId) {
        fetch(`/api/users/${userId}`, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('editUserId').value = data.id;
            document.getElementById('editUserName').value = data.name;
            document.getElementById('editUserEmail').value = data.email;
            document.getElementById('editUserStatus').value = data.is_active;
            document.getElementById('editUserRole').value = data.role;
        })
        .catch(error => console.error("Lỗi khi lấy thông tin người dùng:", error));
    }


    function validateForm() {
        let isValid = true;

        // Clear previous error messages
        const errorMessages = document.querySelectorAll('.text-danger');
        errorMessages.forEach(msg => msg.textContent = '');

        // Initialize an empty string for error messages
        let errorText = '';

        // Validate product name
        const name = document.getElementById('editUserName').value;
        if (!name) {
            errorText += 'Tên người dùng là bắt buộc !<br>';
            isValid = false;
        }
        // Display error messages if validation fails
        if (!isValid) {
            document.getElementById('editUserError').innerHTML = errorText;
        }
        return isValid;
    }
    function updateUser() {
        if (!validateForm()) return;
        const userId = document.getElementById('editUserId').value;
        const name = document.getElementById('editUserName').value;
        const email = document.getElementById('editUserEmail').value;
        const image = document.getElementById('editUserImage').files[0];
        const is_active = document.getElementById('editUserStatus').value;
        const role = document.getElementById('editUserRole').value;
        const formData = new FormData();
        formData.append('name', name);
        formData.append('email', email);
        formData.append('role', role);
        formData.append('is_active', is_active);
        if (image) {
            formData.append('image', image);
        }
        fetch(`/admin/update-user?id=${userId}`, {
            method: 'POST',
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (response.ok) {
                alert("Cập nhật người dùng thành công!");
                $('#ModalUP').modal('hide');
                getData();
            } else {
                alert("Cập nhật người dùng thất bại.");
            }
        })
        .catch(error => console.error("Lỗi khi cập nhật người dùng:", error));
    }
</script>
