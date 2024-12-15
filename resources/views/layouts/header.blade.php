<nav class="main-header navbar navbar-expand navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item" id="sidebar-toggle-button">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        @if (session()->has('auth'))
            <li class="nav-item dropdown">
                <button class="nav-link" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-gear"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a href="{{ route('profile') }}"
                            class="dropdown-item d-flex align-items-center justify-content-between">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mr-2"
                                style="width: 24px; height: 24px; background-color: #e5e5e5 !important;">
                                <i class="fas fa-user text-muted" style="font-size: 14px;"></i>
                            </div>
                            <span class="flex-grow-1">Tài khoản</span>
                            <i class="fas fa-chevron-right ml-2"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('logout') }}"
                            class="dropdown-item d-flex align-items-center justify-content-between">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mr-2"
                                style="width: 24px; height: 24px; background-color: #e5e5e5 !important;">
                                <i class="fas fa-right-from-bracket text-muted" style="font-size: 14px;"></i>
                            </div>
                            <span class="flex-grow-1">Đăng xuất</span>
                            <i class="fas fa-chevron-right ml-2"></i>
                        </a>
                    </li>
                </ul>
            </li>
        @else
            <li class="nav-item">
                <button type="button" class="nav-link" data-bs-toggle="modal" data-bs-target="#loginModal">
                    <i class="fas fa-right-to-bracket"></i>
                </button>
            </li>
        @endif
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
