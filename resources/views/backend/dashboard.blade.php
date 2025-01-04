@extends('backend/layouts/app-admin')

@section('main')
<main class="app-content">
    <div class="row">
      <div class="col-md-12">
        <div class="app-title">
          <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="#"><b>Bảng điều khiển</b></a></li>
          </ul>
          <div id="clock"></div>
        </div>
      </div>
    </div>
    <div class="row">
      <!--Left-->
      <div class="col-md-12 col-lg-6">
        <div class="row">
          <!-- col-6 -->
          <div class="col-md-6">
            <div class="widget-small primary coloured-icon"><i class='icon bx bxs-user-account fa-3x'></i>
              <div class="info">
                <h4>Tổng khách hàng</h4>
                <p><b>{{ $countuser }} khách hàng</b></p>
                <p class="info-tong">Tổng số khách hàng được quản lý.</p>
              </div>
            </div>
          </div>
          <!-- col-6 -->
          <div class="col-md-6">
            <div class="widget-small info coloured-icon"><i class='icon bx bxs-data fa-3x'></i>
              <div class="info">
                <h4>Tổng sản phẩm</h4>
                <p><b>{{ $countproduct }} sản phẩm</b></p>
                <p class="info-tong">Tổng số sản phẩm được quản lý.</p>
              </div>
            </div>
          </div>
          <!-- col-6 -->
          <div class="col-md-6">
            <div class="widget-small warning coloured-icon"><i class='icon bx bxs-shopping-bags fa-3x'></i>
              <div class="info">
                <h4>Tổng đơn hàng</h4>
                <p><b>{{$countorder }} đơn hàng</b></p>
                <p class="info-tong">Tổng số hóa đơn bán hàng trong tháng.</p>
              </div>
            </div>
          </div>
          <!-- col-6 -->
          <div class="col-md-6">
            <div class="widget-small danger coloured-icon"><i class='icon bx bxs-error-alt fa-3x'></i>
              <div class="info">
                <h4>Sắp hết </h4>
                <p><b>{{ $hethang }} Product hết hàng</b></p>
                <p class="info-tong">Số sản phẩm cảnh báo hết cần nhập thêm.</p>
              </div>
            </div>
          </div>
          <!-- col-12 -->
          <div class="col-md-12">
            <div class="tile">
              <h3 class="tile-title">Tình trạng đơn hàng</h3>
              <div>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>ID đơn hàng</th>
                      <th>Tên khách hàng</th>
                      <th>Tổng tiền</th>
                      <th>Trạng thái</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($newsorder as $item)
                    <tr>
                      <td>{{ $item['id'] ?? 'N/A' }}</td> <!-- Kiểm tra null cho ID -->
                      <td>{{ $item['name'] ?? 'Không có tên' }}</td> <!-- Kiểm tra null cho tên khách hàng -->
                      <td>{{ $item['final_price'] ?? 'Không có thông tin' }}</td> <!-- Kiểm tra null cho tổng tiền -->
                      <td>{{ $item['payment_status'] ?? 'Không xác định' }}</td> <!-- Kiểm tra null cho trạng thái -->
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="tile">
              <h3 class="tile-title">Khách hàng mới</h3>
              <div>
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Tên khách hàng</th>
                      <th>Ngày tạo</th>
                      <th>Số điện thoại</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($newsuser as $item)
                    <tr>
                      <td>{{ $item['id'] ?? 'N/A' }}</td> <!-- Kiểm tra null cho ID -->
                      <td>{{ $item['name'] ?? 'Không có tên' }}</td> <!-- Kiểm tra null cho tên khách hàng -->
                      <td>{{ $item['created_at'] ?? 'Không có thông tin' }}</td> <!-- Kiểm tra null cho ngày tạo -->
                      <td>
                        <span class="badge bg-info">
                          {{ $item['number'] ?? 'Không xác định' }} <!-- Kiểm tra null cho số điện thoại -->
                        </span>
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
    </div>

    <div class="text-center" style="font-size: 13px">
      <p><b>Copyright
          <script type="text/javascript">
            document.write(new Date().getFullYear());
          </script> Phần mềm quản lý bán hàng | Dev By Quocdz
        </b></p>
    </div>
  </main>
@endsection
