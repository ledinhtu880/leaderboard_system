@extends('layouts.master')

@section('title', 'Thống kê')

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
    <!-- Content header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Thống kê</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h2 class="card-title text-bold" style="font-size: 24px">Biểu đồ</h2>
                </div>
                <div class="card-body">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-center flex-column">
                                <iframe
                                    src="http://localhost:8088/explore/?slice_id=232&form_data=%7B%22slice_id%22%3A%20232%7D&standalone=true"
                                    height="300" frameborder="0"></iframe>
                                <h6 class="mt-2">Biểu đồ thống kê tỷ lệ vắng</h6>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-center flex-column">
                                <iframe
                                    src="http://localhost:8088/explore/?slice_id=233&form_data=%7B%22slice_id%22%3A%20233%7D&standalone=true"
                                    height="300" frameborder="0"></iframe>
                                <h6 class="mt-2">Biểu đồ thống kê tỷ lệ phát biểu</h6>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center justify-content-center flex-column">
                                <iframe
                                    src="http://localhost:8088/explore/?slice_id=234&form_data=%7B%22slice_id%22%3A%20234%7D&standalone=true"
                                    width="100%" height="400" frameborder="0"></iframe>
                                <h6 class="mt-2">Biểu đồ thống kê điểm project</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h2 class="card-title text-bold" style="font-size: 24px">Thống kê sinh viên</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="membersTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">STT</th>
                                    <th>Họ và tên</th>
                                    <th>Mã sinh viên</th>
                                    <th>Lớp</th>
                                    <th>Điểm project</th>
                                    <th>Số lần phát biểu</th>
                                    <th>Số lần vắng</th>
                                    <th class="text-center">Xem chi tiết</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($members as $each)
                                    <tr>
                                        <td class="text-center">{{ $each['STT'] }}</td>
                                        <td>{{ $each['Họ'] . ' ' . $each['Tên'] }}</td>
                                        <td>{{ $each['Mã sinh viên'] }}</td>
                                        <td>{{ $each['Lớp'] }}</td>
                                        <td>
                                            {{ floor($each['Điểm project']) == $each['Điểm project']
                                                ? number_format($each['Điểm project'], 0)
                                                : number_format($each['Điểm project'], 1, '.', '') }}
                                        </td>
                                        <td>{{ $each['Vắng'] }}</td>
                                        <td>{{ $each['Phát biểu'] }}</td>
                                        <td class="text-center">
                                            <button type="button" data-bs-toggle="modal"
                                                data-bs-target="#modalDetail{{ $each['STT'] }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                            <div class="modal fade" id="modalDetail{{ $each['STT'] }}"
                                                data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                                aria-labelledby="modalDetailLabel{{ $each['STT'] }}" aria-hidden="true">
                                                <div class="modal-dialog modal-xl modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5"
                                                                id="modalDetailLabel{{ $each['STT'] }}">
                                                                {{ $each['Họ'] . ' ' . $each['Tên'] }}
                                                            </h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="info-box">
                                                                        <span class="info-box-icon bg-olive"><i
                                                                                class="fas fa-user-clock"></i></span>
                                                                        <div class="info-box-content">
                                                                            <span class="info-box-text">Điểm chuyên
                                                                                cần</span>
                                                                            <span
                                                                                class="info-box-number">{{ $each['Điểm chuyên cần'] }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="info-box">
                                                                        <span class="info-box-icon bg-teal"><i
                                                                                class="far fa-comment-dots"></i></span>
                                                                        <div class="info-box-content">
                                                                            <span class="info-box-text">Điểm phát
                                                                                biểu</span>
                                                                            <span
                                                                                class="info-box-number">{{ $each['Điểm phát biểu'] }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="info-box">
                                                                        <span class="info-box-icon bg-success"><i
                                                                                class="fas fa-chart-bar"></i></span>
                                                                        <div class="info-box-content">
                                                                            <span class="info-box-text">Điểm tổng</span>
                                                                            <span
                                                                                class="info-box-number">{{ $each['Điểm tổng'] }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2"></div>
                                                                <div class="col-md-4">
                                                                    <div class="info-box">
                                                                        <span class="info-box-icon bg-lightblue"><i
                                                                                class="fas fa-graduation-cap"></i></span>
                                                                        <div class="info-box-content">
                                                                            <span class="info-box-text">GPA (Hệ 10)</span>
                                                                            <span class="info-box-number">
                                                                                @if (session('msv') == $each['Mã sinh viên'])
                                                                                    {{ session('gpa') }}
                                                                                @else
                                                                                    {{ !array_key_exists('gpa', $each) || is_null($each['gpa']) ? 'Chưa có thông tin' : $each['gpa'] }}
                                                                                @endif
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="info-box">
                                                                        <span class="info-box-icon bg-primary"><i
                                                                                class="fas fa-medal"></i></span>
                                                                        <div class="info-box-content">
                                                                            <span class="info-box-text">Ranking</span>
                                                                            <span class="info-box-number">
                                                                                {{ $each['ranking'] }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                searching: true,
                info: true,
                autoWidth: false,
                responsive: true,
                ordering: false, // Tắt sorting
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/vi.json'
                },
            });

            // Ghi đè hàm tìm kiếm
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                // Giá trị cột "name" và "msv"
                var name = data[1] || ""; // Lấy dữ liệu từ cột thứ 2 (name)
                var msv = data[2] || ""; // Lấy dữ liệu từ cột thứ 3 (msv)
                var searchValue = $('#membersTable_filter input').val() || ""; // Lấy giá trị ô tìm kiếm

                // Kiểm tra xem giá trị tìm kiếm có khớp với name hoặc msv không
                return (
                    name.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").indexOf(
                        searchValue.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "")
                    ) > -1 ||
                    msv.toLowerCase().indexOf(searchValue.toLowerCase()) > -1
                );
            });

            // Gọi lại hàm filter khi nhập giá trị
            $('#membersTable_filter input').on('keyup', function() {
                table.draw();
            });

            $('#sidebar-toggle-button').on('click', function() {
                setTimeout(() => {
                    table.columns.adjust().draw(false); // Cập nhật lại table
                }, 0); // Đợi animation của sidebar kết thúc
            });
        });
    </script>
@endpush
