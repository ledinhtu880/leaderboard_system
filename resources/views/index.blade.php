@extends('layouts.master')

@section('title', 'Phân chia nhóm')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="menu-item rounded shadow">
                    <a href="{{ route('clusterView') }}" class="menu-link">
                        <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span class="menu-text">Phân chia nhóm</span>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="menu-item rounded shadow">
                    <a href="#" class="menu-link">
                        <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2">
                            </path>
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                            <line x1="12" y1="11" x2="12" y2="17"></line>
                            <line x1="9" y1="14" x2="15" y2="14"></line>
                        </svg>
                        <span class="menu-text">Xem danh sách nhóm</span>
                    </a>
                </div>
            </div>
            {{-- Xem thành viên nào thuộc nhóm nào, người dùng đăng nhập thì sẽ xem mình được gợi ý ở nhóm nào --}}
        </div>
    </div>
@endsection
