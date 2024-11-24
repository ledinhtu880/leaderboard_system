@extends('layouts.master')

@section('title', 'Phân chia nhóm')

@section('content')
    <div class="container">
        @if (session('role') == 'admin')
            <div class="row">
                <div class="col-md-3">
                    <div class="menu-item rounded shadow">
                        <a href="{{ route('clusterView') }}" class="menu-link">
                            <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <line x1="19" y1="8" x2="19" y2="14" />
                                <line x1="16" y1="11" x2="22" y2="11" />
                            </svg>
                            <span class="menu-text">Phân chia nhóm</span>
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="menu-item rounded shadow">
                        <a href="{{ route('groupDashboard') }}" class="menu-link">
                            <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            </svg>
                            <span class="menu-text">Danh sách nhóm</span>
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="menu-item rounded shadow">
                        <a href="{{ route('userDashboard') }}" class="menu-link">
                            <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <line x1="16" y1="5" x2="22" y2="5" />
                                <line x1="16" y1="9" x2="22" y2="9" />
                                <line x1="16" y1="13" x2="22" y2="13" />
                            </svg>
                            <span class="menu-text">Danh sách thành viên</span>
                        </a>
                    </div>
                </div>
            </div>
        @else
            Người dùng
        @endif
    </div>
@endsection
