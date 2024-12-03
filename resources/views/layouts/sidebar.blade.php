<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
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
                    <small>{{ session('role') }}</small>
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
         with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Trang chủ</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
