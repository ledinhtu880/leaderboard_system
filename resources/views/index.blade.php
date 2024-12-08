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
        @if (session('role') == 'admin')
            <div class="col-md-3 mb-3">
                <div class="menu-item bg-info rounded shadow">
                    <a href="{{ route('admin.members') }}" class="menu-link">
                        <i class="menu-icon fas fa-user-friends fa-2x"></i>
                        <span class="menu-text">Danh sách thành viên</span>
                    </a>
                </div>
            </div>
        @endif
        <div class="col-md-3 mb-3">
            <div class="menu-item bg-info rounded shadow">
                <a href="{{ route('member.profile') }}" class="menu-link">
                    <i class="menu-icon fas fa-calendar fa-2x"></i>
                    <span class="menu-text">Lịch học</span>
                </a>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="menu-item bg-info rounded shadow">
                <a href="{{ route('member.attendance') }}" class="menu-link">
                    <i class="menu-icon fas fa-check-to-slot fa-2x"></i>
                    <span class="menu-text">Điểm danh</span>
                </a>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection
