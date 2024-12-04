@extends('layouts.master')

@section('title', 'Danh sách thành viên')
@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Danh sách thành viên</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Danh sách thành viên</li>
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
                @foreach ($topics as $topic)
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm hover-shadow">
                            <div class="card-header bg-gradient-light d-flex justify-content-between align-items-center">
                                <h3 class="card-title mb-0 text-primary">
                                    <i class="fas fa-bookmark me-2"></i>{{ $topic->name }}
                                </h3>
                                <span
                                    class="badge {{ $topic->members->count() >= 5 ? 'bg-danger' : 'bg-success' }} px-3 py-2">
                                    <i class="fas fa-users me-1"></i>{{ $topic->members->count() }}/5
                                </span>
                            </div>
                            <div class="card-body">
                                <p class="card-text text-muted">
                                    <i class="fas fa-info-circle me-2"></i>{{ $topic->description }}
                                </p>
                                <h5 class="mt-4 mb-3 text-secondary">
                                    <i class="fas fa-user-friends me-2"></i>Thành viên
                                </h5>
                                <div class="list-group">
                                    @foreach ($topic->members as $memberTopic)
                                        <div
                                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-user-circle me-2 text-primary"></i>
                                                {{ $memberTopic->member->name }}
                                            </div>
                                            <button class="btn btn-outline-success btn-sm rounded-pill">
                                                <i class="fas fa-check me-1"></i>Duyệt
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="card-footer bg-light d-flex justify-content-end">
                                <button class="btn btn-primary btn-sm rounded-pill">
                                    <i class="fas fa-cog me-1"></i>Quản lý
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@push('js')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            function calculateAverage(data, key) {
                if (data.length === 0) return 0;
                const sum = data.reduce((acc, curr) => acc + parseFloat(curr[key] || 0), 0);
                return (sum / data.length).toFixed(2);
            }
        });
    </script>
@endpush
