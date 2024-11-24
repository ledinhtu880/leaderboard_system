<header class="header-container">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <a href="/" class="d-flex align-items-center link-body-emphasis text-decoration-none">
                <h5 class="brand-title">Cụm 2</h5>
            </a>
            <div class="dropdown text-end">
                <a href="#" class="d-block text-decoration-none dropdown-toggle user-dropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <span class="account-avatar rounded-circle">
                            {{ session('firstCharacter') }}
                        </span>
                        <div class="d-flex flex-column ms-2 text-start">
                            <span class="account-name">
                                {{ session('name') }}
                            </span>
                            <span class="account-role">
                                Sinh viên
                            </span>
                        </div>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-small">
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-person me-2"></i>Tài khoản
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}">
                            <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
