@extends('layouts.master')

@section('title', 'Trang chủ')

@section('content')
    <!-- Content header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Trang chủ</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="row mx-3">
        <div class="col-md-3 mb-3">
            <div class="menu-item bg-info rounded shadow">
                <a href="{{ route('user.topics') }}" class="menu-link">
                    <i class="menu-icon fas fa-book fa-2x"></i>
                    <span class="menu-text">Chọn đề tài</span>
                </a>
            </div>
        </div>
        @if (session('role') == 'admin')
            {{-- <div class="col-md-3 mb-3">
                <div class="menu-item bg-info rounded shadow">
                    <a href="{{ route('clusterView') }}" class="menu-link">
                        <i class="menu-icon fas fa-project-diagram fa-2x"></i>
                        <span class="menu-text">Phân chia nhóm</span>
                    </a>
                </div>
            </div> --}}
            <div class="col-md-3 mb-3">
                <div class="menu-item bg-info rounded shadow">
                    <a href="{{ route('admin.topics') }}" class="menu-link">
                        <i class="menu-icon fas fa-tasks fa-2x"></i>
                        <span class="menu-text">Danh sách đề tài</span>
                    </a>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="menu-item bg-info rounded shadow">
                    <a href="{{ route('admin.groups') }}" class="menu-link">
                        <i class="menu-icon fas fa-users fa-2x"></i>
                        <span class="menu-text">Danh sách nhóm</span>
                    </a>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="menu-item bg-info rounded shadow">
                    <a href="{{ route('admin.members') }}" class="menu-link">
                        <i class="menu-icon fas fa-user-friends fa-2x"></i>
                        <span class="menu-text">Danh sách thành viên</span>
                    </a>
                </div>
            </div>
        @endif
    </div>
    <!-- /.content -->
@endsection
