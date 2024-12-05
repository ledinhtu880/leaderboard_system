<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
        <img src="https://sinhvien1.tlu.edu.vn/assets/images/logo-small.png" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3 object-fit-cover" style="opacity: 0.8" />
        <span class="brand-text font-weight-light">Cụm 2</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image d-flex align-items-center justify-content-center">
                <span class="user-avatar img-circle elevation-2 text-white">{{ session('firstCharacter') }}</span>
            </div>
            <div class="info">
                <a href="#" class="d-flex flex-column">
                    <strong>{{ session('name') }}</strong>
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link{{ request()->is('/') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Trang chủ</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.topics') }}"
                        class="nav-link{{ Str::startsWith(request()->url(), url('/user/topics')) ? ' active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Chọn đề tài</p>
                    </a>
                </li>
                @if (session('role') == 'admin')
                    {{-- <li class="nav-item">
                        <a href="{{ route('clusterView') }}"
                            class="nav-link{{ Str::startsWith(request()->url(), url('/cluster')) ? ' active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Danh sách chủ đề</p>
                        </a>
                    </li> --}}
                    <li class="nav-item">
                        <a href="{{ route('admin.topics') }}"
                            class="nav-link{{ Str::startsWith(request()->url(), url('/admin/topics')) ? ' active' : '' }}">
                            <i class="nav-icon fas fa-tasks"></i>
                            <p>Danh sách đề tài</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.groups') }}"
                            class="nav-link{{ Str::startsWith(request()->url(), url('/admin/groups')) ? ' active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Danh sách nhóm</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.members') }}"
                            class="nav-link{{ Str::startsWith(request()->url(), url('/admin/users')) ? ' active' : '' }}">
                            <i class="nav-icon fas fa-user-friends"></i>
                            <p>Danh sách thành viên</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
