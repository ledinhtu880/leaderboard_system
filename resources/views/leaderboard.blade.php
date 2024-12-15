@extends('layouts.master')

@section('title', 'Trang chủ')

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
    <!-- Content header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 mb-3">
                    <h1 class="m-0">Bảng xếp hạng</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-lightblue card-outline">
                <div class="card-header">
                    <h2 class="card-title text-bold" style="font-size: 24px">Bảng xếp hạng</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3 align-items-center justify-content-center d-flex">
                            <div class="card card-primary card-outline shadow">
                                <div
                                    class="card-header d-flex align-items-center justify-content-center flex-column border-0">
                                    <div class="position-relative">
                                        <h2>🥈</h2>
                                    </div>
                                    <h3 class="card-info">{{ $secondPlace['Họ'] . ' ' . $secondPlace['Tên'] }}</h3>
                                    <h5 class="card-title">{{ $secondPlace['Lớp'] }}</h5>
                                </div>
                                <div class="card-footer bg-white">
                                    <div class="row">
                                        <div class="col-sm-6 mb-3">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="badge rounded-pill badge-info p-2">
                                                    <h6 class="m-0">Điểm chuyên cần:
                                                        {{ $secondPlace['Điểm chuyên cần'] }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="badge rounded-pill badge-primary p-2">
                                                    <h6 class="m-0">Điểm phát biểu:
                                                        {{ $secondPlace['Điểm phát biểu'] }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="badge rounded-pill badge-success p-2">
                                                    <h6 class="m-0">Điểm tổng: {{ $secondPlace['Điểm tổng'] }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-5 align-items-center justify-content-center d-flex">
                            <div class="card card-danger card-outline shadow">
                                <div
                                    class="card-header d-flex align-items-center justify-content-center flex-column border-0">
                                    <div class="position-relative">
                                        <h1>🥇</h1>
                                    </div>
                                    <h3 class="card-info">{{ $firstPlace['Họ'] . ' ' . $firstPlace['Tên'] }}</h3>
                                    <h5 class="card-title">{{ $firstPlace['Lớp'] }}</h5>
                                </div>
                                <div class="card-footer bg-white">
                                    <div class="row">
                                        <div class="col-sm-6 mb-3">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="badge rounded-pill badge-info p-2">
                                                    <h6 class="m-0">Điểm chuyên cần:
                                                        {{ $firstPlace['Điểm chuyên cần'] }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="badge rounded-pill badge-primary p-2">
                                                    <h6 class="m-0">Điểm phát biểu:
                                                        {{ $firstPlace['Điểm phát biểu'] }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="badge rounded-pill badge-success p-2">
                                                    <h6 class="m-0">Điểm tổng: {{ $firstPlace['Điểm tổng'] }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 align-items-center justify-content-center d-flex">
                            <div class="card card-success card-outline shadow">
                                <div
                                    class="card-header d-flex align-items-center justify-content-center flex-column border-0">
                                    <div class="position-relative">
                                        <h2>🥉</h2>
                                    </div>
                                    <h3 class="card-info">{{ $thirdPlace['Họ'] . ' ' . $thirdPlace['Tên'] }}</h3>
                                    <h5 class="card-title">{{ $thirdPlace['Lớp'] }}</h5>
                                </div>
                                <div class="card-footer bg-white">
                                    <div class="row">
                                        <div class="col-sm-6 mb-3">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="badge rounded-pill badge-info p-2">
                                                    <h6 class="m-0">Điểm chuyên cần:
                                                        {{ $thirdPlace['Điểm chuyên cần'] }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="badge rounded-pill badge-primary p-2">
                                                    <h6 class="m-0">Điểm phát biểu:
                                                        {{ $thirdPlace['Điểm phát biểu'] }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="badge rounded-pill badge-success p-2">
                                                    <h6 class="m-0">Điểm tổng: {{ $thirdPlace['Điểm tổng'] }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="membersTable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">Thứ hạng</th>
                                            <th scope="col">Họ và tên</th>
                                            <th scope="col">Mã sinh viên</th>
                                            <th scope="col">Lớp</th>
                                            <th scope="col">Điểm chuyên cần</th>
                                            <th scope="col">Điểm phát biểu</th>
                                            <th scope="col">Điểm tổng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($remainingMembers as $each)
                                            <tr>
                                                <td class="text-center text-bold">{{ $each['ranking'] }}</td>
                                                <td>{{ $each['Họ'] . ' ' . $each['Tên'] }}</td>
                                                <td>{{ $each['Mã sinh viên'] }}</td>
                                                <td>{{ $each['Lớp'] }}</td>
                                                <td>{{ $each['Điểm chuyên cần'] }}</td>
                                                <td>{{ $each['Điểm phát biểu'] }}</td>
                                                <td>{{ $each['Điểm tổng'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
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
            // Khởi tạo DataTable
            var table = $('#membersTable').DataTable({
                pageLength: 100,
                scrollY: '400px',
                scrollCollapse: true,
                paging: false,
                searching: false,
                autoWidth: false,
                responsive: true,
                ordering: false, // Tắt sorting
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/vi.json'
                },
            });

            $('#sidebar-toggle-button').on('click', function() {
                setTimeout(() => {
                    table.columns.adjust().draw(false); // Cập nhật lại table
                }, 0); // Đợi animation của sidebar kết thúc
            });
        });
    </script>
@endpush
