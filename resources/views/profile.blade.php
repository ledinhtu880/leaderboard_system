@extends('layouts.master')

@section('title', 'Thông tin cá nhân')

@push('css')
    <style>
        .circle-progress {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto;
        }

        .circular-chart {
            transform: rotate(-90deg);
            width: 100%;
            height: 100%;
        }

        .circular-chart .circle-background {
            fill: none;
            stroke: #e6e6e6;
            stroke-width: 3.8;
        }

        .circular-chart .circle-progress-bar {
            fill: none;
            stroke-width: 3.8;
            stroke-linecap: round;
            transition: stroke-dasharray 1s ease;
        }

        .circle-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 16px;
            font-weight: bold;
        }

        .blue .circle-progress-bar {
            stroke: #007bff;
        }

        .green .circle-progress-bar {
            stroke: #28a745;
        }

        .yellow .circle-progress-bar {
            stroke: #ffc107;
        }
    </style>
@endpush

@section('content')
    <!-- Content header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Xem thông tin</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="d-flex align-items-center justify-content-center">
                                <span
                                    class="user-avatar user-avatar--lg img-circle elevation-2 text-white mb-3">{{ session('firstCharacter') }}</span>
                            </div>
                            <h3 class="profile-username text-center">{{ session('name') }}</h3>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b><i class="fas fa-phone me-2"></i> Số điện thoại</b>
                                    <span class="float-right">{{ $member->phone }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b><i class="fas fa-envelope me-2"></i> Email</b>
                                    <span class="float-right">{{ $member->email }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b><i class="fas fa-calendar me-2"></i> Ngày sinh</b>
                                    <span class="float-right">{{ $member->birthdate }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b><i class="fas fa-users me-2"></i> Lớp</b>
                                    <span class="float-right">{{ $member->class }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- /.profile image -->
                </div>
                <div class="col-md-9">
                    <!-- Card -->
                    <div class="card card-info card-outline">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="progress-group text-center">
                                        <div class="circle-progress">
                                            <svg viewBox="0 0 36 36" class="circular-chart blue">
                                                <path class="circle-background"
                                                    d="M18 2 a16 16 0 1 1 0 32 a16 16 0 1 1 0 -32" />
                                                <path class="circle-progress-bar"
                                                    stroke-dasharray="{{ 33 * $member->{"Vắng"} }}, 100"
                                                    d="M18 2 a16 16 0 1 1 0 32 a16 16 0 1 1 0 -32" />
                                            </svg>
                                            <div class="circle-content">
                                                <b>{{ $member->{"Vắng"} }}</b>/3
                                            </div>
                                        </div>
                                        <p class="mt-2">Số lần vắng</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="progress-group text-center">
                                        <div class="circle-progress">
                                            <svg viewBox="0 0 36 36" class="circular-chart green">
                                                <path class="circle-background"
                                                    d="M18 2 a16 16 0 1 1 0 32 a16 16 0 1 1 0 -32" />
                                                <path class="circle-progress-bar"
                                                    stroke-dasharray="{{ 20 * $member->{"Phát biểu"} }}, 100"
                                                    d="M18 2 a16 16 0 1 1 0 32 a16 16 0 1 1 0 -32" />
                                            </svg>
                                            <div class="circle-content">
                                                <b>{{ $member->{"Phát biểu"} }}</b>/5
                                            </div>
                                        </div>
                                        <p class="mt-2">Số lần phát biểu</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    <!-- Card -->
                    <div class="card card-info card-outline">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-olive"><i class="fas fa-user-clock"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Điểm chuyên cần</span>
                                            <span class="info-box-number">{{ $member->{'Điểm chuyên cần'} }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-teal"><i class="far fa-comment-dots"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Điểm phát biểu</span>
                                            <span class="info-box-number">{{ $member->{"Điểm phát biểu"} }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-success"><i class="fas fa-chart-bar"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Điểm tổng</span>
                                            <span class="info-box-number">{{ $member->{"Điểm tổng"} }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-lightblue"><i class="fas fa-user-graduate"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">GPA (Hệ 4) </span>
                                            <span class="info-box-number"
                                                style="font-size: 18px;">{{ $member->{"gpa4"} }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-primary"><i class="fas fa-graduation-cap"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">GPA (Hệ 10)</span>
                                            <span class="info-box-number"
                                                style="font-size: 18px;">{{ $member->{"gpa10"} }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-gradient-cyan"><i class="fas fa-medal"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Ranking</span>
                                            <span class="info-box-number"
                                                style="font-size: 18px;">{{ $member->{"ranking"} }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
