@extends('layouts.master')

@section('title', 'Thống kê')

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <style>
        .attendance-table {
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .attendance-table th {
            background: #f8f9fa;
            font-size: 0.9rem;
            padding: 10px;
            text-align: center;
        }

        .attendance-table td {
            padding: 10px;
            vertical-align: middle;
        }

        .attendance-table th.current-day {
            background-color: #f0ad4e;
            /* Màu vàng nhạt */
            font-weight: bold;
            color: white;
        }
    </style>
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
                                <h6 class="mt-2">Biểu đồ thể hiện giá trị của điểm project</h6>
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
                                    <x-statistics-table-row :each="$each" />
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
        // Hàm chuyển đổi chuỗi có dấu thành không dấu
        function removeVietnameseAccents(str) {
            return str.normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/đ/g, 'd')
                .replace(/Đ/g, 'D');
        }

        // Mở rộng chức năng tìm kiếm của DataTable
        $.extend($.fn.dataTableExt.ofnSearch, {
            "vietnamese": function(data) {
                return !data ?
                    '' :
                    typeof data === 'string' ?
                    removeVietnameseAccents(data) :
                    data;
            }
        });
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
                // Thêm cấu hình cho tìm kiếm tiếng Việt
                columnDefs: [{
                        targets: 1,
                        searchable: true,
                        type: 'vietnamese',
                    },
                    {
                        targets: 2,
                        searchable: true,
                        type: 'vietnamese',
                    },
                    {
                        searchable: false,
                        targets: '_all',
                    }
                ]
            });

            // Thêm xử lý tìm kiếm tùy chỉnh
            $('.dataTables_filter input').on('keyup', function() {
                var searchTerm = $(this).val();
                table.search(removeVietnameseAccents(searchTerm)).draw();
            });

            $('#sidebar-toggle-button').on('click', function() {
                setTimeout(() => {
                    table.columns.adjust().draw(false); // Cập nhật lại table
                }, 0); // Đợi animation của sidebar kết thúc
            });

            $('.absence-mark, .participation-mark, .legend .badge').tooltip({
                placement: 'top',
                container: 'body',
                animation: true
            });
        });
    </script>
@endpush
