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
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
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
            <div class="card">
                <div class="card-body">
                    <table id="membersTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Tên</th>
                                <th>GPA</th>
                                <th>GPA kỳ gần nhất</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($members as $member)
                                @php
                                    $gpaChange = $member->gpa - $member->last_gpa;
                                    $trendClass = $gpaChange > 0 ? 'positive' : ($gpaChange < 0 ? 'negative' : '');
                                    $trendIcon = $gpaChange > 0 ? '↑' : ($gpaChange < 0 ? '↓' : '→');
                                @endphp
                                <tr class="member-row">
                                    <td>{{ $member->name }}</td>
                                    <td>{{ number_format($member->gpa, 2) }}</td>
                                    <td>{{ number_format($member->last_gpa, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card stats-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stats-icon bg-primary">
                                        <i class="fa-solid fa-chart-line text-white"></i>
                                    </div>
                                    <div class="stats-value" id="avgGPA">-</div>
                                    <div class="stats-label">GPA Trung bình</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card stats-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stats-icon bg-info bg-opacity-10">
                                        <i class="fas fa-users text-white"></i>
                                    </div>
                                    <div class="stats-value" id="totalMembers">-</div>
                                    <div class="stats-label">Tổng số thành viên</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#cover-spin").show();

            function calculateAverage(data, key) {
                if (data.length === 0) return 0;
                const sum = data.reduce((acc, curr) => acc + parseFloat(curr[key] || 0), 0);
                return (sum / data.length).toFixed(2);
            }

            function updateStats(api) {
                let filteredData;

                // Kiểm tra xem có tìm kiếm hay không
                if (api.search() !== '') {
                    filteredData = api.rows({
                        search: 'applied'
                    }).data().toArray();
                } else {
                    filteredData = api.rows({
                        page: 'current'
                    }).data().toArray();

                }

                $('#avgGPA').text(calculateAverage(filteredData, 1));
                $('#totalMembers').text(filteredData.length);

                // Cập nhật hiển thị số lượng thành viên sau khi thay đổi
                $('.stats-card').each(function() {
                    $(this).addClass('border-primary');
                    setTimeout(() => {
                        $(this).removeClass('border-primary');
                    }, 500);
                });

                // Kiểm tra và ẩn/hiện phân trang
                if (filteredData.length <= api.page.len()) {
                    $('.dataTables_paginate').hide();
                } else {
                    $('.dataTables_paginate').show();
                }
            }

            const table = $('#membersTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/vi.json'
                },
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
                scrollY: '300px',
                scrollCollapse: true,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Tất cả"]
                ],
                columnDefs: [{
                    targets: 0,
                    orderable: false
                }, ],
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                    '<"row"<"col-sm-12"tr>>' +
                    '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                drawCallback: function() {
                    updateStats(this.api());
                },
                initComplete: function() {
                    updateStats(this.api());
                    $("#cover-spin").hide();
                }
            });

            table.on('search.dt', function() {
                updateStats(table);
            });

            table.on('length.dt', function() {
                updateStats(table);
            });

            table.on('page.dt', function() {
                updateStats(table);
            });

            $('#membersTable_filter input').unbind()
                .bind('keyup', function(e) {
                    if (e.keyCode == 13) {
                        table.search(this.value).draw();
                    }
                });

            $('#sidebar-toggle-button').on('click', function() {
                setTimeout(() => {
                    table.columns.adjust().draw(false); // Cập nhật lại table
                }, 0); // Đợi animation của sidebar kết thúc
            });
        });
    </script>
@endpush
