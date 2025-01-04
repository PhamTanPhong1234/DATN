@extends('frontend.layouts.app-user')

@section('main')
<section class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-content">
                    <h1 class="breadcrumb-hrading">Lịch sử đơn hàng</h1>
                    <ul class="breadcrumb-links">
                        <li><a href="index.html">Trang chủ</a></li>
                        <li>Lịch sử đơn hàng</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="shop-category-area order-history">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 order-lg-last col-md-12 order-md-first">
                <div class="order-history-table">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Mã đơn hàng</th>
                                <th>Người đặt</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Thanh toán</th>
                                <th>Phương thức thanh toán</th>
                                <th>Ghi chú</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr class="accordion-toggle">
                                <td>Mã đơn hàng: {{ $order['order_code'] ?? 'Chưa cập nhật' }}</td>
                                <td>{{ $order['name'] ?? 'Chưa cập nhật' }}</td>
                                <td>{{ \Carbon\Carbon::parse($order['created_at'])->format('d/m/Y') ?? 'Chưa cập nhật' }}</td>
                                <td>{{ number_format($order['total_price'] ?? 0, 0, ',', '.') }} VND</td>
                                <td>
                                    @if($order['status'] == 0)
                                    Đang xử lý
                                    @elseif($order['status'] == 1)
                                    Chờ giao hàng
                                    @else
                                    Đã hủy
                                    @endif
                                </td>
                                <td>{{ $order['payment_status'] ?? 'Chưa cập nhật' }}</td>
                                <td>{{ $order['payment_method'] ?? 'Chưa cập nhật' }}</td>
                                <td>{{ $order['note'] ?? 'Không có' }}</td>
                                <td class="flex flex-col-reverse">
                                    <a class="view-details-btn" href="javascript:void(0)" data-toggle="collapse" data-target="#order-details-{{ $order['id'] }}">
                                        Xem chi tiết <i class="fa fa-eye"></i>  
                                    </a>
                                    <a class="remove-details-btn" href="javascript:void(0)" data-order-id="{{ $order['id'] }}">
                                        Hủy đơn hàng <i class="fa fa-times-circle"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7" class="hiddenRow">
                                    <div class="accordian-body collapse" id="order-details-{{ $order['id'] }}">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Sản phẩm</th>
                                                    <th>Số lượng</th>
                                                    <th>Giá</th>
                                                    <th>Tổng cộng</th>
                                                    <th>Chi tiết</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($order['order_items'] as $item)
                                                <tr>
                                                    <td>{{ $item['product']['name'] ?? 'Chưa có tên sản phẩm' }}</td>
                                                    <td>{{ $item['quantity'] ?? 'Chưa có' }}</td>
                                                    <td>{{ number_format($item['price'] ?? 0, 0, ',', '.') }} VND</td>
                                                    <td>{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 0, ',', '.') }} VND</td>
                                                    <td>
                                                        <a href="{{ route('single-product', ['id' => $item['product_id']]) }}">
                                                            Xem sản phẩm
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
     
        $(document).on('click', '.view-details-btn', function() {
            const target = $(this).data('target');
            $(target).collapse('toggle');
        });

        // Hủy đơn hàng
        $(document).on('click', '.remove-details-btn', function() {
            var orderId = $(this).data('order-id');
            if (confirm('Bạn có chắc muốn hủy đơn hàng này?')) {
                $.ajax({
                    url: '{{ route('cancel-order') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        orderId: orderId
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert('Hủy đơn hàng thất bại. Vui lòng thử lại!');
                        }
                    },
                    error: function(xhr) {
                        alert('Có lỗi xảy ra. Vui lòng thử lại!');
                    }
                });
            }
        });
    });
</script>
<script>

</script>
@endpush
