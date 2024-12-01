@extends('layouts.master')

@section('title', 'Danh sách thành viên')
@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <style>
        table.dataTable>thead .sorting:after,
        table.dataTable>thead .sorting_asc:after,
        table.dataTable>thead .sorting_desc:after,
        table.dataTable>thead .sorting_asc_disabled:after,
        table.dataTable>thead .sorting_desc_disabled:after {
            left: -.5em;
            right: 0px;
            content: "↓";
        }

        table.dataTable>thead .sorting:before,
        table.dataTable>thead .sorting_asc:before,
        table.dataTable>thead .sorting_desc:before,
        table.dataTable>thead .sorting_asc_disabled:before,
        table.dataTable>thead .sorting_desc_disabled:before {
            left: -1em;
            right: 0px;
            content: "↑";
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Danh sách thành viên</h5>
            </div>
            <div class="card-body">
                <table id="membersTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Tên</th>
                            <th>GPA</th>
                            <th>GPA kỳ gần nhất</th>
                            <th>Điểm cuối kỳ</th>
                            <th>Tính cách</th>
                            <th>Sở thích</th>
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
                                <td>{{ number_format($member->final_score, 2) }}</td>
                                <td>
                                    <span class="badge bg-info badge-personality">
                                        {{ $member->personality == 0 ? 'Hướng nội' : 'Hướng ngoại' }}
                                    </span>
                                </td>
                                <td>{{ $member->hobby }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="stats-icon bg-primary bg-opacity-10">
                                    <i class="fas fa-chart-line text-primary"></i>
                                </div>
                                <div class="stats-value" id="avgGPA">-</div>
                                <div class="stats-label">GPA Trung bình</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="stats-icon bg-success bg-opacity-10">
                                    <i class="fas fa-graduation-cap text-success"></i>
                                </div>
                                <div class="stats-value" id="avgFinalScore">-</div>
                                <div class="stats-label">Điểm cuối kỳ TB</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="stats-icon bg-info bg-opacity-10">
                                    <i class="fas fa-users text-info"></i>
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
                $('#avgFinalScore').text(calculateAverage(filteredData, 3));
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
                    },
                    {
                        targets: 4,
                        orderable: false
                    },
                    {
                        targets: 5,
                        orderable: false
                    }
                ],
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
        });
    </script>
@endpush
