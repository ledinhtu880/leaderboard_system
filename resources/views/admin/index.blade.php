@extends('layouts.master')

@section('title', 'Danh sách nhóm')

@section('content')
    <div class="container-fluid p-4">
        <div class="row">
            @foreach ($result['suggested_groups'] as $groupName => $group)
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">{{ Str::upper(str_replace('_', ' ', $groupName)) }}</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="bg-light p-3 border-bottom">
                                <h6 class="mb-2">Thống kê nhóm:</h6>
                                <small>
                                    <div>GPA TB: {{ number_format($group['stats']['avg_gpa'] ?? 0, 2) }}</div>
                                    <div>Điểm TB: {{ number_format($group['stats']['avg_final_score'] ?? 0, 2) }}</div>
                                    <div>Sở thích: {{ implode(', ', $group['stats']['hobbies'] ?? []) }}</div>
                                </small>
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
                                            $groupScore = collect($memberScores['scores'])->firstWhere(
                                                'group_id',
                                                (int) substr($groupName, 6),
                                            );
                                            $scoreValue = $groupScore['score'] ?? 0;
                                        }

                                        $scoreClass = match (true) {
                                            $scoreValue >= 80 => 'bg-success-subtle',
                                            $scoreValue >= 60 => 'bg-warning-subtle',
                                            default => 'bg-danger-subtle',
                                        };
                                    @endphp

                                    <li class="list-group-item {{ $scoreClass }}">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <strong>{{ $member['name'] }}</strong>
                                            <span class="badge bg-info">{{ $member['personality'] ?? 'N/A' }}</span>
                                        </div>
                                        <div class="small">
                                            <div class="row">
                                                <div class="col-6">GPA: {{ $member['gpa'] ?? 'N/A' }}</div>
                                                <div class="col-6">Last: {{ $member['last_gpa'] ?? 'N/A' }}</div>
                                            </div>
                                            <div class="row mt-1">
                                                <div class="col-6">Final: {{ $member['final_score'] ?? 'N/A' }}</div>
                                                <div class="col-6">Hobby: {{ $member['hobby'] ?? 'N/A' }}</div>
                                            </div>
                                            <div class="text-end mt-1">
                                                <small class="text-muted">
                                                    Tương thích: {{ number_format($scoreValue, 1) }}%
                                                </small>
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
