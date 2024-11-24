@extends('layouts.master')

@section('title', 'Danh sách nhóm')

@section('content')
    <div class="container-fluid py-4">
        <div class="row g-4">
            @foreach ($result['suggested_groups'] as $groupName => $group)
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-primary bg-gradient text-white py-3">
                            <h5 class="card-title mb-0 fw-bold">
                                {{ Str::upper(str_replace('_', ' ', $groupName)) }}
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="bg-light p-3 border-bottom">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="fas fa-chart-bar me-2"></i>Thống kê nhóm
                                </h6>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <div class="small text-muted me-2">GPA TB:</div>
                                            <div class="fw-semibold">{{ number_format($group['stats']['avg_gpa'] ?? 0, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <div class="small text-muted me-2">Điểm TB:</div>
                                            <div class="fw-semibold">
                                                {{ number_format($group['stats']['avg_final_score'] ?? 0, 2) }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 d-flex gap-1">
                                    <span class="small text-muted mb-1">Sở thích:</span>
                                    <div class="d-flex flex-wrap align-items-center justify-content-center gap-1">
                                        @foreach ($group['stats']['hobbies'] ?? [] as $hobby)
                                            <span
                                                class="badge bg-primary bg-opacity-10 text-primary">{{ $hobby }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">
                                @foreach ($group['members'] as $member)
                                    @php
                                        $memberScores = collect($result['compatibility_scores'])->firstWhere(
                                            'member_id',
                                            $member['id'],
                                        );

                                        $groupScore = null;
                                        $scoreValue = 0;

                                        if ($memberScores && isset($memberScores['scores'])) {
                                            $groupId = (int) str_replace('group_', '', $groupName);
                                            $groupScore = collect($memberScores['scores'])->firstWhere(
                                                'group_id',
                                                $groupId,
                                            );
                                            $scoreValue = $groupScore['score'] ?? 0;
                                        }

                                        $scoreClass = match (true) {
                                            $scoreValue >= 80 => 'border-success bg-success-subtle',
                                            $scoreValue >= 60 => 'border-warning bg-warning-subtle',
                                            default => 'border-danger bg-danger-subtle',
                                        };
                                    @endphp

                                    <li class="list-group-item {{ $scoreClass }} border-start-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0 fw-bold">{{ $member['name'] }}</h6>
                                            <span class="badge bg-info text-white px-2 py-1">
                                                {{ $member['personality'] ?? 'N/A' }}
                                            </span>
                                        </div>

                                        <div class="row g-2 small">
                                            <div class="col-6">
                                                <div class="d-flex justify-content-between border-bottom pb-1">
                                                    <span class="text-muted">GPA tổng:</span>
                                                    <span class="fw-semibold">{{ $member['gpa'] ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex justify-content-between border-bottom pb-1">
                                                    <span class="text-muted">GPA kỳ gần nhất:</span>
                                                    <span class="fw-semibold">{{ $member['last_gpa'] ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex justify-content-between border-bottom pb-1">
                                                    <span class="text-muted">Điểm QTHT:</span>
                                                    <span class="fw-semibold">{{ $member['final_score'] ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex justify-content-between border-bottom pb-1">
                                                    <span class="text-muted">Sở thích:</span>
                                                    <span class="fw-semibold">{{ $member['hobby'] ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-2 text-end">
                                            @php
                                                $compatibilityClass = match (true) {
                                                    $scoreValue >= 80 => 'text-success',
                                                    $scoreValue >= 60 => 'text-warning',
                                                    default => 'text-danger',
                                                };
                                            @endphp
                                            <div class="small {{ $compatibilityClass }}">
                                                Tương thích: <strong>{{ number_format($scoreValue, 1) }}%</strong>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
