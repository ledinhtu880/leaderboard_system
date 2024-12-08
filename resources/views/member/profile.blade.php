@extends('layouts.master')

@section('title', 'Trang chủ')

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
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Xem thông tin</li>
                    </ol>
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
                            <p class="text-muted text-center">
                                {{ $member->user->role == 'admin' ? 'Quản trị viên' : 'Thành viên' }}</p>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Số điện thoại</b> <span class="float-right">{{ $member->phone }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Email</b> <span class="float-right">{{ $member->email }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Ngày sinh</b> <span class="float-right">{{ $member->birth_date }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Lớp</b> <span class="float-right">{{ $member->class }}</span>
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
                            {{-- <div class="row">
                                <div class="col-md-4">
                                    <div class="progress-group">
                                        Số lần điểm danh
                                        <span class="float-right"><b>5</b>/15</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-primary" style="width: 33%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="progress-group">
                                        Điểm Project
                                        <span class="float-right"><b>7</b>/10</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" style="width: 70%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="progress-group">
                                        Số lần xung phong
                                        <span class="float-right"><b>3</b>/5</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-warning" style="width: 60%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="progress-group text-center">
                                        <div class="circle-progress">
                                            <svg viewBox="0 0 36 36" class="circular-chart blue">
                                                <path class="circle-background"
                                                    d="M18 2 a16 16 0 1 1 0 32 a16 16 0 1 1 0 -32" />
                                                <path class="circle-progress-bar" stroke-dasharray="33, 100"
                                                    d="M18 2 a16 16 0 1 1 0 32 a16 16 0 1 1 0 -32" />
                                            </svg>
                                            <div class="circle-content">
                                                <b>5</b>/15
                                            </div>
                                        </div>
                                        <p class="mt-2">Số lần điểm danh</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="progress-group text-center">
                                        <div class="circle-progress">
                                            <svg viewBox="0 0 36 36" class="circular-chart green">
                                                <path class="circle-background"
                                                    d="M18 2 a16 16 0 1 1 0 32 a16 16 0 1 1 0 -32" />
                                                <path class="circle-progress-bar" stroke-dasharray="70, 100"
                                                    d="M18 2 a16 16 0 1 1 0 32 a16 16 0 1 1 0 -32" />
                                            </svg>
                                            <div class="circle-content">
                                                <b>7</b>/10
                                            </div>
                                        </div>
                                        <p class="mt-2">Điểm Project</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="progress-group text-center">
                                        <div class="circle-progress">
                                            <svg viewBox="0 0 36 36" class="circular-chart yellow">
                                                <path class="circle-background"
                                                    d="M18 2 a16 16 0 1 1 0 32 a16 16 0 1 1 0 -32" />
                                                <path class="circle-progress-bar" stroke-dasharray="60, 100"
                                                    d="M18 2 a16 16 0 1 1 0 32 a16 16 0 1 1 0 -32" />
                                            </svg>
                                            <div class="circle-content">
                                                <b>3</b>/5
                                            </div>
                                        </div>
                                        <p class="mt-2">Số lần xung phong</p>
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
