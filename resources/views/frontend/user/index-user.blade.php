<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin người dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .user-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .user-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 20px;
        }
        .user-info h2, .user-info p {
            margin: 0;
            padding: 5px 0;
        }
    </style>
</head>

<body>
    @extends('frontend.layouts.app-user')

    @section('main')
    <section>
        <div class="user-container text-center">
            <img src="{{ Auth::user()->avatar ?? asset('images/avatar/default-avatar.jpg') }}" alt="Avatar" class="user-avatar">
            <div class="user-info">
                <h2>{{ Auth::user()->name ?? 'Chưa cập nhật' }}</h2>
                <p>Email: {{ Auth::user()->email ?? 'Chưa cập nhật' }}</p>
                <p>Số điện thoại: {{ Auth::user()->number ?? 'Chưa cập nhật' }}</p>
                <p>Địa chỉ: {{ Auth::user()->address ?? 'Chưa cập nhật' }}</p>
                <p>Ngày tạo: {{ Auth::user()->created_at ?? 'Chưa cập nhật' }}</p>
                <p>Ngày cập nhật: {{ Auth::user()->updated_at ?? 'Chưa cập nhật' }}</p>
                <p>Vai trò: {{ Auth::user()->role ?? 'Chưa cập nhật' }}</p>
         
                <p>IP: {{ $user->log_login[0]['ip_address']  ?? "Không xác định"}}</p>
                <p>Device: {{ $user->log_login[0]['device']  ?? "Không xác định" }}</p>

                <p>Trạng thái tài khoản: {{ Auth::user()->is_ban ? 'Bị cấm' : 'Hoạt động' }}</p>
            </div>
            <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#editModal">Chỉnh sửa thông tin</button>
        </div>

        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Chỉnh sửa thông tin</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm">
                            @csrf
                            <input type="hidden" id="userId" value="{{ Auth::user()->id ?? '' }}">
                            <div class="mb-3">
                                <label for="editAvatar" class="form-label">Avatar</label>
                                <input type="file" class="form-control" id="editAvatar" name="avatar">
                            </div>
                            <div class="mb-3">
                                <label for="editName" class="form-label">Tên</label>
                                <input type="text" class="form-control" id="editName" name="name" value="{{ Auth::user()->name ?? '' }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="editPhone" class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" id="editPhone" name="phone" value="{{ Auth::user()->number ?? '' }}">
                            </div>
                            <div class="mb-3">
                                <label for="editAddress" class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control" id="editAddress" name="address" value="{{ Auth::user()->address ?? '' }}">
                            </div>
                            <div class="mb-3">
                                <label for="editAddress" class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control" id="editAddress" name="address" value="{{ Auth::user()->address ?? '' }}">
                            </div>
                            <button type="button" class="btn btn-primary" id="saveChanges">Lưu thay đổi</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto">Thông báo</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body" id="toastMessage">
                    Thông tin đã được cập nhật!
                </div>
            </div>
        </div>
    </section>
    @endsection

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const saveChangesButton = document.getElementById('saveChanges');
            const toastElement = document.getElementById('toast');
            const toastMessageElement = document.getElementById('toastMessage');
            const toast = new bootstrap.Toast(toastElement);

            if (saveChangesButton) {
                saveChangesButton.addEventListener('click', function() {
                    const form = document.getElementById('editForm');
                    const formData = new FormData(form);

                    formData.append('userId', document.getElementById('userId').value);

                    fetch('/update/user', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            toastMessageElement.textContent = data.message;
                            toast.show();    
                            location.reload(); 
                        } else {
                            toastMessageElement.textContent = data.message;
                            toast.show();
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi:', error);
                        toastMessageElement.textContent = 'Có lỗi xảy ra!';
                        toast.show();
                    });
                });
            }
        });
    </script>
</body>
</html>
