@extends('layouts.master')

@section('title', 'Điểm danh')

@push('css')
    <style>
        .attendance-card {
            transition: all 0.3s ease;
        }

        .attendance-card:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-current {
            border-left: 5px solid #28a745;
        }

        .card-upcoming {
            border-left: 5px solid #007bff;
        }

        .card-past {
            opacity: 0.7;
        }
    </style>
@endpush

@section('content')
    <!-- Content header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Điểm danh</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Điểm danh</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline mb-5">
                <div class="card-body">
                    <!-- Buổi Học Hiện Tại -->
                    <div class="card attendance-card mb-3 card-current">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title">Lớp Toán - Lập Trình</h5>
                                    <p class="card-text text-muted">Hôm nay, 08/12/2024 | 13:30 - 15:30</p>
                                </div>
                                <div>
                                    <button class="btn btn-success" id="attendanceBtn">
                                        <i class="fas fa-check-circle mr-2"></i>Điểm Danh
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Buổi Học Sắp Tới -->
                    <div class="card attendance-card mb-3 card-upcoming">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title">Lớp Vật Lý</h5>
                                    <p class="card-text text-muted">15/12/2024 | 09:00 - 11:00</p>
                                </div>
                                <div>
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-lock mr-2"></i>Chưa Mở Điểm Danh
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-info card-outline">
                <div class="card-body">
                    <!-- Buổi Học Sắp Tới Khác -->
                    <div class="card attendance-card mb-3 card-upcoming">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title">Hệ thống kinh doanh thông minh</h5>
                                    <p class="card-text text-muted">Hôm nay 10/12/2024 | 09:45 => </p>
                                </div>
                                <div>
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-lock mr-2"></i>Chưa Mở Điểm Danh
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buổi Học Đã Qua -->
                    <div class="card attendance-card mb-3 card-past">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title">Lớp Lịch Sử</h5>
                                    <p class="card-text text-muted">01/12/2024 | 10:00 - 12:00</p>
                                </div>
                                <div>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check mr-2"></i>Đã Điểm Danh
                                    </span>
                                    <span class="badge bg-warning ml-2">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>Muộn
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thêm nhiều buổi học khác -->
                    <div class="text-center">
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-list mr-2"></i>Xem Toàn Bộ Lịch Học
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
