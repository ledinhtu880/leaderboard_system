<header>
    <nav class="navbar navbar-expand-lg custom-navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-graduation-cap me-2"></i>
                Hệ thống chia nhóm
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="user-menu" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-avatar">
                        {{ session('firstCharacter') }}
                    </div>
                    <div class="user-info">
                        <p class="user-name">{{ session('name') }}</p>
                        <p class="user-role">{{ ucfirst(session('role')) }}</p>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" id="logout-btn" class="dropdown-item">
                                    Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>


{{-- <ul class="navbar-nav mx-auto">
                    <li
                        class="nav-item {{ Str::startsWith(request()->url(), url('/admin/groups/')) ? ' active' : '' }}">
                        <a class="nav-link" href="{{ route('groupDashboard') }}">
                            <i class="fas fa-users me-1"></i>Danh sách nhóm
                        </a>
                    </li>
                    <li class="nav-item {{ Str::startsWith(request()->url(), url('/admin/users/')) ? ' active' : '' }}">
                        <a class="nav-link" href="{{ route('userDashboard') }}">
                            <i class="fas fa-users me-1"></i>Danh sách thành viên
                        </a>
                    </li>
                    <li class="nav-item {{ Str::startsWith(request()->url(), url('/admin/users/')) ? ' active' : '' }}">
                        <a class="nav-link" href="{{ route('user.topics') }}">
                            <i class="fas fa-users me-1"></i>Danh sách thành viên
                        </a>
                    </li>
                </ul> --}}
