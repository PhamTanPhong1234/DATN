<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
  <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="{{ Auth::user()->avatar }}" width="50px"
      alt="User Image">
    <div>
      <p class="app-sidebar__user-name"><b>{{ Auth::user()->name }}</b></p>
      <p class="app-sidebar__user-designation">Chào mừng bạn trở lại</p>
    </div>
  </div>
  <hr>
  <ul class="app-menu">
     

      {{-- BẢNG ĐIỀU KHIỂN  --}}
      <li><a class="app-menu__item active" href="{{ route('dashboard') }}"><i class='app-menu__icon bx bx-tachometer'></i><span
          class="app-menu__label">Bảng điều khiển</span></a>
      </li>

      {{-- QUẢN LÍ KHÁCH HÀNG  --}}
      <li><a class="app-menu__item " href="{{ route('user.admin') }}"><i class='app-menu__icon bx bx-id-card'></i> <span
          class="app-menu__label">Quản lý khách hàng</span></a>
      </li>

      {{-- QUẢN LÍthể loại --}}
      <li><a class="app-menu__item" href="/admin/categories"><i
          class='app-menu__icon bx bx-purchase-tag-alt'></i><span class="app-menu__label">Quản lý danh mục</span></a>
      </li>

      <li><a class="app-menu__item" href="/admin/products"><i
            class='app-menu__icon bx bx-purchase-tag-alt'></i><span class="app-menu__label">Quản lý sản phẩm</span></a>
      </li>


      <li><a class="app-menu__item" href="/admin/news"><i
            class='app-menu__icon bx bx-purchase-tag-alt'></i><span class="app-menu__label">Quản lý tin tức</span></a>
      </li>

      <li><a class="app-menu__item" href="/admin/artist"><i
            class='app-menu__icon bx bx-purchase-tag-alt'></i><span class="app-menu__label">Quản lý nghệ sĩ</span></a>
      </li>
      <li><a class="app-menu__item" href="/admin/orders"><i class='app-menu__icon bx bx-task'></i><span
          class="app-menu__label">Quản lý đơn hàng</span></a>
      </li>
      <li>
        <a class="app-menu__item" href="/admin/messages">
          <i class="app-menu__icon bx bx-envelope"></i>
          <span class="app-menu__label">Quản lý tin nhắn</span>
        </a>
      </li>
      

  </ul>
</aside>