@extends('layouts.master')

@section('title', 'Bảng xếp hạng')

@push('css')
    <style>
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        /* Top 1 Styling */
        .top-1-card {
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            border-radius: 12px;
        }

        .top-1-card .card-body {
            position: relative;
            z-index: 1;
        }

        .top-1-card::before {
            /* content: '👑'; */
            content: '🥇';
            font-size: 90px;
            position: absolute;
            top: -20px;
            right: -20px;
            opacity: 0.2;
            transform: rotate(16deg);
        }

        .top-2-card {
            background: linear-gradient(135deg, #E8E8E8 0%, #B4B4B4 100%);
            border-radius: 12px;
        }

        .top-2-card::before {
            content: '🥈';
            font-size: 80px;
            position: absolute;
            top: -20px;
            right: -20px;
            opacity: 0.2;
            transform: rotate(16deg);
        }

        .top-3-card {
            background: linear-gradient(135deg, #CD7F32 0%, #8B4513 100%);
            border-radius: 12px;
        }

        .top-3-card::before {
            content: '🥉';
            font-size: 80px;
            position: absolute;
            top: -20px;
            right: -20px;
            opacity: 0.2;
            transform: rotate(16deg);
        }

        .score-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .ribbon-wrapper.ribbon-lg {
            height: 120px;
            width: 120px;
        }

        .ribbon-wrapper.ribbon-lg .ribbon {
            right: -2px;
            top: 40px;
            width: 160px;
            padding: 7px 0;
            font-size: 16px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        /* Regular cards styling */
        .regular-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .member-count {
            background: rgba(0, 0, 0, 0.1);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
        }

        .score-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.9);
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Bảng xếp hạng nhóm</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Top 1 -->
            <div class="row justify-content-center mb-4">
                @foreach ($data as $group)
                    @if ($group['ranking'] === 1)
                        <div class="col-md-6">
                            <div class="card top-1-card shadow-lg">
                                <div class="card-body text-center p-3">
                                    <div class="score-circle">
                                        <h2 class="mb-0 text-warning">{{ number_format($group['averageScore'], 2) }}</h2>
                                    </div>
                                    <h3 class="mb-3 text-white">{{ $group['name'] }}</h3>
                                    <div class="d-flex align-items-center justify-content-center gap-4 flex-column">
                                        <span class="member-count text-white">
                                            <i class="fas fa-users me-2"></i>{{ $group['memberCount'] }} thành viên
                                        </span>
                                        <button class="btn btn-light w-75" data-bs-toggle="modal"
                                            data-bs-target="#groupModal{{ $group['ranking'] }}">
                                            <i class="fas fa-eye me-2"></i>Xem thành viên
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Top 2-3 -->
            <div class="row justify-content-center mb-4">
                @foreach ($data as $group)
                    @if ($group['ranking'] === 2 || $group['ranking'] === 3)
                        <div class="col-md-5">
                            <div class="card {{ $group['ranking'] === 2 ? 'top-2-card' : 'top-3-card' }} shadow">
                                <div class="card-body text-center p-3">
                                    <div class="score-circle">
                                        <h3 class="mb-0">{{ number_format($group['averageScore'], 2) }}</h3>
                                    </div>
                                    <h4 class="mb-3 text-white">{{ $group['name'] }}</h4>
                                    <span class="member-count text-white">
                                        <i class="fas fa-users me-2"></i>{{ $group['memberCount'] }} thành viên
                                    </span>
                                    <button class="btn btn-light mt-3 w-75" data-bs-toggle="modal"
                                        data-bs-target="#groupModal{{ $group['ranking'] }}">
                                        <i class="fas fa-eye me-2"></i>Xem thành viên
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Remaining Groups -->
            <div class="row">
                @foreach ($data as $group)
                    @if ($group['ranking'] > 3)
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-body text-center p-3">
                                    <h6 class="badge bg-secondary position-absolute top-0 start-0 m-2">
                                        #{{ $group['ranking'] }}
                                    </h6>
                                    <div class="score-circle">
                                        <h3 class="mb-0">{{ number_format($group['averageScore'], 2) }}</h3>
                                    </div>
                                    <h4 class="mb-3 text-dark">{{ $group['name'] }}</h4>
                                    <span class="member-count bg-secondary text-white">
                                        <i class="fas fa-users me-2"></i>{{ $group['memberCount'] }} thành viên
                                    </span>
                                    <button class="btn btn-dark mt-3 w-75" data-bs-toggle="modal"
                                        data-bs-target="#groupModal{{ $group['ranking'] }}">
                                        <i class="fas fa-eye me-2"></i>Xem thành viên
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
    <!-- Modals for each group -->
    @foreach ($data as $group)
        <div class="modal fade" id="groupModal{{ $group['ranking'] }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $group['name'] }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>MSSV</th>
                                        <th>Họ và tên</th>
                                        <th>Điểm tổng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($group['members'] as $index => $member)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $member['Mã sinh viên'] }}</td>
                                            <td>{{ $member['Họ'] . ' ' . $member['Tên'] }}</td>
                                            <td>{{ number_format($member['Điểm tổng'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('.modal').on('shown.bs.modal', function() {
                $(this).find('.table').DataTable({
                    "pageLength": 5,
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Vietnamese.json"
                    }
                });
            });
        });
    </script>
@endpush
