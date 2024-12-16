<aside class="main-sidebar sidebar-dark-gray elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('leaderboard') }}" class="brand-link">
        <img src="https://sinhvien1.tlu.edu.vn/assets/images/logo-small.png" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3 object-fit-cover" style="opacity: 0.8" />
        <span class="brand-text font-weight-light">Nhóm 2</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
            <div class="image d-flex align-items-center justify-content-center">
                <span class="user-avatar img-circle elevation-2 text-white">
                    {{ session('auth') ? session('firstCharacter') : 'U' }}</span>
            </div>
            <div class="info">
                <a href="#" class="d-flex flex-column">
                    {{ session('auth') ? session('name') : 'Người dùng' }}
                </a>
            </div>
            {{-- <div class="nav nav-pills nav-sidebar flex-column">
                <li class="nav-item">
                    <a href="{{ route('leaderboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-right-to-bracket"></i>
                        <p>Đăng nhập</p>
                    </a>
                </li>
            </div> --}}
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                @if (session()->has('auth'))
                    <li class="nav-item">
                        <a href="{{ route('statistics') }}"
                            class="nav-link{{ Str::startsWith(request()->url(), url('/statistics')) ? ' active' : '' }}">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>Thống kê</p>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('leaderboard') }}" class="nav-link{{ request()->is('/') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-ranking-star"></i>
                        <p>Bảng xếp hạng</p>
                    </a>
                </li>
                @if (session()->has('auth'))
                    <li class="nav-item">
                        <a href="{{ route('profile') }}"
                            class="nav-link{{ Str::startsWith(request()->url(), url('/profile')) ? ' active' : '' }}">
                            <i class="nav-icon fas fa-user"></i>
                            <p>Thông tin cá nhân</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
