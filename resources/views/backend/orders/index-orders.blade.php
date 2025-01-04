@extends('backend/layouts/app-admin')

@section('main')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <main class="app-content">
        <div class="app-title">
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item active"><a href="#"><b>Danh sách đơn hàng</b></a></li>
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
                                    <th width="10"><input type="checkbox" id="all"></th>
                                    <th>Mã đơn hàng</th>
                                    <th>Người nhận</th>
                                    <th>Order Code</th>
                                    <th>Tổng tiền</th>
                                    <th>Sản phẩm</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="body-data-order">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="ModalUP" tabindex="-1" aria-labelledby="ModalUPLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalUPLabel">Sửa Đơn Hàng</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            onclick="closeModal()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editOrderForm">
                            <div class="form-group">
                                <label for="editOrderId">Mã Đơn Hàng</label>
                                <input type="text" class="form-control" id="editOrderId" disabled>
                            </div>
                            <div class="form-group">
                                <label for="editOrderTotal">Tổng Tiền</label>
                                <input type="number" class="form-control" id="editOrderTotal">
                            </div>
                            <div class="form-group">
                                <label for="editOrderStatus">Trạng Thái</label>
                                <select class="form-control" id="editOrderStatus">
                                    <option value="0">Chưa xử lý</option>
                                    <option value="1">Đang vân chuyển</option>
                                    <option value="2">Hủy</option>
                                </select>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="updateOrder()">Cập Nhật</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        getOrdersData();
    });

    function displayOrders(orders) {
        const dataForm = document.getElementById('body-data-order');
        dataForm.innerHTML = '';
        orders.forEach(order => {
            let productsHtml = '';
            order.order_items.forEach(item => {
                productsHtml += `
                    <tr>
            <td><strong>Tên sản phẩm:</strong> ${item.product.name}</td>
            <td><strong>Giá tiền:</strong> ${item.price}</td>
        </tr>
                `;
            });
            const html = `
                <tr>
                    <td><input type="checkbox" name="check${order.id}" value="${order.id}" class="order-checkbox"></td>
                    <td>${order.order_code}</td>
                    <td>${order.name}</td>
                    <td>${order.order_code}</td>
                    <td>${order.final_price}</td>
                    <td>
                        <table>
                            ${productsHtml}
                        </table>
                    </td>
                    <td>${order.status}</td>
                    <td>
                        <button class="btn btn-primary btn-sm trash" type="button" title="Xóa" onclick="deleteOrder(${order.id})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        <button class="btn btn-primary btn-sm edit" type="button" title="Sửa" onclick="editOrder(${order.id})" data-toggle="modal" data-target="#ModalUP">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
            `;
            dataForm.innerHTML += html;
        });
    }

    function editOrder(orderId) {
        fetch(`/admin/single-orders/${orderId}`)
            .then(response => response.json())
            .then(data => {
                const order = data;
                document.getElementById('editOrderId').value = order.id;
                document.getElementById('editOrderTotal').value = order.final_price;
                document.getElementById('editOrderStatus').value = order.status;
            })
            .catch(error => {
                console.error("Lỗi khi lấy thông tin đơn hàng:", error);
                alert("Không thể tải thông tin đơn hàng.");
            });
    }

    function getOrdersData() {
        fetch('/admin/orders-index')
            .then(response => response.json())
            .then(data => displayOrders(data))
            .catch(error => {
                console.error("Lỗi khi lấy dữ liệu đơn hàng:", error);
                alert("Không thể tải danh sách đơn hàng.");
            });
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
        document.getElementById('editOrderId').value = '';
        document.getElementById('editOrderTotal').value = '';
        document.getElementById('editOrderStatus').value = '';
    }

    function deleteOrder(orderId) {
        if (!confirm("Bạn có chắc chắn muốn xóa đơn hàng này?")) {
            return;
        }

        fetch(`/admin/orders/${orderId}`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (response.ok) {
                    alert("Xóa đơn hàng thành công!");
                    getOrdersData();
                } else {
                    alert("Xóa đơn hàng thất bại.");
                }
            })
            .catch(error => {
                console.error("Lỗi khi xóa đơn hàng:", error);
                alert("Lỗi khi xóa đơn hàng.");
            });
    }

   

    function updateOrder() {
        const id = document.getElementById('editOrderId').value;
        const totalAmount = document.getElementById('editOrderTotal').value;
        const status = document.getElementById('editOrderStatus').value;

        const formData = new FormData();
        formData.append('id', id);
        formData.append('total_amount', totalAmount);
        formData.append('status', status);

        fetch(`/admin/orders-update/${id}`, {
                method: 'POST',
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (response.ok) {
                    alert("Cập nhật đơn hàng thành công!");
                    closeModal();
                    getOrdersData();
                } else {
                    alert("Cập nhật đơn hàng thất bại.");
                }
            })
            .catch(error => {
                console.error("Lỗi khi cập nhật đơn hàng:", error);
                alert("Lỗi khi cập nhật đơn hàng.");
            });
    }
</script>
