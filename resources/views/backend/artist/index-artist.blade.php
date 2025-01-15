@extends('backend/layouts/app-admin')

@section('main')

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<main class="app-content">
    <div class="app-title">
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item active"><a href="#"><b>Danh sách nghệ sĩ</b></a></li>
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
                            <a class="btn btn-success btn-sm" onclick="openModal()" data-toggle="modal" data-target="#ModalAddArtist" type="button">
                                <i class="fas fa-plus"></i> Thêm nghệ sĩ
                            </a>
                        </div>
                    </div>
                    <table class="table table-hover table-bordered" id="sampleTable">
                        <thead>
                            <tr>
                                <th width="10"><input type="checkbox" id="selectAll"></th>
                                <th>ID</th>
                                <th>Tên</th>
                                <th>Mô tả</th>
                                <th>Ảnh</th>
                                <th>Trạng thái</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody id="body-data-artists"></tbody>
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
                            <h5>Chỉnh sửa thông tin nghệ sĩ</h5>
                        </div>
                        <div id="editArtistError" class="text-danger w-100 text-center"></div>
                        <input hidden class="form-control" type="text" id="editArtistId" readonly>
                    </div>
                    <div class="row">
                        
                        <div class="form-group col-md-12">
                            <label class="control-label">Tên nghệ sĩ</label>
                            <input class="form-control" type="text" id="editArtistName">
                        </div>
                        <div class="form-group col-md-12">
                            <label class="control-label">Mô tả</label>
                            <textarea class="form-control" id="editArtistBio"></textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Ảnh</label>
                            <input class="form-control" type="file" id="editArtistImage" accept="image/*">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Trạng thái</label>
                            <select class="form-control" id="editArtistStatus">
                                <option value="1">Hoạt động</option>
                                <option value="0">Không hoạt động</option>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-save" type="button" id="save-edit-artist">Lưu lại</button>
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
        document.getElementById('save-edit-artist').addEventListener('click', updateArtist);
    });

    function displayArtists(artists) {
        const dataForm = document.getElementById('body-data-artists');
        dataForm.innerHTML = '';

        artists.forEach(artist => {
            const html = `
                <tr>
                    <td><input type="checkbox" class="artist-checkbox" value="${artist.id}"></td>
                    <td>${artist.id}</td>
                    <td>${artist.name}</td>
                    <td>${artist.bio}</td>
                 <td><img src="{{ asset('${artist.image_path}') }}" alt="Category Image" style="max-width: 100px; max-height: 100px;"></td>
                    <td>${artist.status}</td>
                    <td>
                        <button class="btn btn-primary btn-sm trash" onclick="deleteArtist(${artist.id})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        <button class="btn btn-primary btn-sm edit" onclick="editArtist(${artist.id})" data-toggle="modal" data-target="#ModalUP">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
            `;
            dataForm.innerHTML += html;
        });
    }

    function getData() {
        fetch('/admin/artist-index')
            .then(response => response.json())
            .then(data => displayArtists(data))
            .catch(error => console.error("Lỗi khi lấy dữ liệu:", error));
    }

    function clearData() {
        document.getElementById('editArtistId').value = '';
        document.getElementById('editArtistName').value = '';
        document.getElementById('editArtistBio').value = '';
        document.getElementById('editArtistImage').value = '';
        document.getElementById('editArtistStatus').value = '';
    }

    function editArtist(artistId) {
        fetch(`/admin/artist-single?id=${artistId}`)
            .then(response => response.json())
            .then(data => {
                const artist = data[0];
                document.getElementById('editArtistId').value = artist.id;
                document.getElementById('editArtistName').value = artist.name;
                document.getElementById('editArtistBio').value = artist.bio;
                document.getElementById('editArtistStatus').value = artist.status;
            })
            .catch(error => console.error("Lỗi khi lấy thông tin nghệ sĩ:", error));
    }

    function deleteArtist(artistId) {
        if (!confirm("Bạn có chắc chắn muốn xóa nghệ sĩ này?")) return;

        fetch(`/admin/artist-remove?id=${artistId}`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (response.ok) {
                    alert("Xóa nghệ sĩ thành công!");
                    getData();
                } else {
                    alert("Xóa nghệ sĩ thất bại.");
                }
            })
            .catch(error => console.error("Lỗi khi xóa nghệ sĩ:", error));
    }

    function validateForm() {
        let isValid = true;

        // Clear previous error messages
        const errorMessages = document.querySelectorAll('.text-danger');
        errorMessages.forEach(msg => msg.textContent = '');

        // Initialize an empty string for error messages
        let errorText = '';

        // Validate product name
        const name = document.getElementById('editArtistName').value;
        if (!name) {
            errorText += 'Tên nghệ sĩ là bắt buộc !<br>';
            isValid = false;
        }
        // Validate price
        const content = document.getElementById('editArtistBio').value;
        if (!content) {
            errorText += 'Vui lòng nhập mô tả nghệ sĩ !<br>';
            isValid = false;
        } else if (content.lenght < 50) {
            errorText += 'Vui lòng nội dung ít nhất 50 kí tự !<br>';
            isValid = false;
        }

        // Display error messages if validation fails
        if (!isValid) {
            document.getElementById('editArtistError').innerHTML = errorText;
        }

        return isValid;
    }

    function updateArtist() {
        if (!validateForm()) return;
        const id = document.getElementById('editArtistId').value;
        const name = document.getElementById('editArtistName').value;
        const bio = document.getElementById('editArtistBio').value;
        const image = document.getElementById('editArtistImage').files[0];
        const status = document.getElementById('editArtistStatus').value;

        const formData = new FormData();
        formData.append('id', id);
        formData.append('name', name);
        formData.append('bio', bio);
        if (image) formData.append('image', image);
        formData.append('status', status);

        fetch(`/admin/artist-update`, {
                method: 'POST',
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (response.ok) {
                    alert("Cập nhật nghệ sĩ thành công!");
                    closeModal();
                    getData();
                } else {
                    alert("Cập nhật nghệ sĩ thất bại.");
                }
            })
            .catch(error => console.error("Lỗi khi cập nhật nghệ sĩ:", error));
    }

    function openModal() {
        clearData();
        $('#ModalUP').modal('show');
    }

    function closeModal() {
        $('#ModalUP').modal('hide');
        clearData();
    }
</script>