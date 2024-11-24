@extends('layouts.master')

@section('title', 'Danh sách nhóm')

@push('css')
    <style>
        .border-start-3 {
            border-left-width: 3px !important;
        }

        .card {
            transition: transform 0.2s;
        }

        .member-drag-handle {
            cursor: grab;
            padding: 0.5rem;
            margin: -0.5rem;
            margin-bottom: 0.5rem;
            border-radius: 0.25rem;
        }

        .member-drag-handle:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .member-item.sortable-ghost {
            opacity: 0.5;
            background-color: #e9ecef;
        }

        .member-item.sortable-chosen {
            background-color: #f8f9fa;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .member-list {
            min-height: 50px;
        }

        .member-count {
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all 0.3s ease;
        }

        .member-count.bg-warning {
            animation: pulse 1.5s infinite;
        }

        .border-start-3 {
            border-left-width: 3px !important;
        }

        .card {
            transition: transform 0.2s;
        }

        .member-drag-handle {
            cursor: grab;
            padding: 0.5rem;
            margin: -0.5rem;
            margin-bottom: 0.5rem;
            border-radius: 0.25rem;
        }

        .member-drag-handle:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .member-item.sortable-ghost {
            opacity: 0.5;
            background-color: #e9ecef;
        }

        .member-item.sortable-chosen {
            background-color: #f8f9fa;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .member-list {
            min-height: 50px;
        }
    </style>
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <!-- Save Button -->
        <div class="d-flex justify-content-end mb-4">
            <button id="saveChanges" class="btn btn-primary" disabled>
                <i class="fas fa-save me-2"></i>Lưu thay đổi
            </button>
        </div>

        <div class="row g-4" id="groupContainer">
            @foreach ($result as $groupName => $group)
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-primary bg-gradient text-white py-3 d-flex justify-content-between">
                            <h5 class="card-title mb-0 fw-bold">
                                {{ Str::upper(str_replace('_', ' ', $groupName)) }}
                            </h5>
                            <span class="member-count badge bg-white text-primary">
                                <span class="count">{{ count($group['members']) }}</span>/5
                            </span>
                        </div>

                        <div class="card-body p-0">
                            <!-- Group Statistics -->
                            <div class="bg-light p-3 border-bottom">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="fas fa-chart-bar me-2"></i>Thống kê nhóm
                                </h6>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <div class="small text-muted me-2">GPA TB:</div>
                                            <div class="fw-semibold group-avg-gpa">
                                                {{ number_format($group['stats']['avg_gpa'] ?? 0, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <div class="small text-muted me-2">Điểm TB:</div>
                                            <div class="fw-semibold group-avg-score">
                                                {{ number_format($group['stats']['avg_final_score'] ?? 0, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 d-flex align-items-center gap-2">
                                    <div class="small text-muted mb-1">Sở thích:</div>
                                    <div class="d-flex flex-wrap gap-1 group-hobbies">
                                        @foreach ($group['stats']['hobbies'] ?? [] as $hobby)
                                            <span
                                                class="badge bg-primary bg-opacity-10 text-primary">{{ $hobby }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Member List -->
                            <ul class="list-group list-group-flush member-list" data-group-id="{{ substr($groupName, 6) }}">
                                @foreach ($group['members'] as $member)
                                    <li class="list-group-item member-item" data-member-id="{{ $member['id'] }}"
                                        data-member-gpa="{{ $member['gpa'] }}"
                                        data-member-final="{{ $member['final_score'] }}"
                                        data-member-hobby="{{ $member['hobby'] }}">
                                        <div class="member-drag-handle">
                                            <i class="fas fa-grip-vertical text-muted me-2"></i>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0 fw-bold">{{ $member['name'] }}</h6>
                                            <span class="badge bg-primary text-white px-2 py-1">
                                                {{ $member['personality'] == 0 ? 'Hướng nội' : 'Hướng ngoại' }}
                                            </span>
                                        </div>

                                        <div class="row g-2 small">
                                            <div class="col-6">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted">GPA:</span>
                                                    <span class="fw-semibold">{{ $member['gpa'] ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted">Last:</span>
                                                    <span class="fw-semibold">{{ $member['last_gpa'] ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted">Final:</span>
                                                    <span class="fw-semibold">{{ $member['final_score'] ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted">Hobby:</span>
                                                    <span class="fw-semibold">{{ $member['hobby'] ?? 'N/A' }}</span>
                                                </div>
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

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        $(document).ready(function() {
            let hasChanges = false;
            const $saveButton = $('#saveChanges');

            // Initialize Sortable on each list
            $('.member-list').each(function() {
                new Sortable(this, {
                    group: 'shared-groups',
                    animation: 150,
                    handle: '.member-drag-handle',
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    onEnd: function(evt) {
                        const $fromGroup = $(evt.from);
                        const $toGroup = $(evt.to);

                        // Update both groups
                        updateGroupStats($fromGroup);
                        if (evt.from !== evt.to) {
                            updateGroupStats($toGroup);
                        }

                        // Check member count and show warning if needed
                        checkMemberCount($toGroup);

                        hasChanges = true;
                        $saveButton.prop('disabled', false);
                    }
                });
            });

            // Function to check member count
            function checkMemberCount($group) {
                const memberCount = $group.find('.member-item').length;
                const groupName = $group.closest('.card').find('.card-title').text().trim();

                // Update member count badge
                $group.closest('.card').find('.member-count .count').text(memberCount);

                // Show warning if more than 5 members
                if (memberCount > 5) {
                    // Add visual indicator
                    $group.closest('.card').find('.member-count')
                        .removeClass('bg-white text-primary')
                        .addClass('bg-warning text-dark');
                } else {
                    // Remove visual indicator
                    $group.closest('.card').find('.member-count')
                        .addClass('bg-white text-primary')
                        .removeClass('bg-warning text-dark');
                }
            }

            // Function to update group statistics
            function updateGroupStats($group) {
                const $members = $group.find('.member-item');
                let totalGPA = 0;
                let totalFinal = 0;
                const hobbies = new Set();

                $members.each(function() {
                    totalGPA += parseFloat($(this).data('member-gpa') || 0);
                    totalFinal += parseFloat($(this).data('member-final') || 0);
                    if ($(this).data('member-hobby')) {
                        hobbies.add($(this).data('member-hobby'));
                    }
                });

                const $card = $group.closest('.card');
                const memberCount = $members.length;
                const avgGPA = memberCount ? (totalGPA / memberCount).toFixed(2) : '0.00';
                const avgFinal = memberCount ? (totalFinal / memberCount).toFixed(2) : '0.00';

                $card.find('.group-avg-gpa').text(avgGPA);
                $card.find('.group-avg-score').text(avgFinal);

                // Update hobbies
                const $hobbiesContainer = $card.find('.group-hobbies');
                $hobbiesContainer.empty();
                hobbies.forEach(hobby => {
                    $hobbiesContainer.append(
                        `<span class="badge bg-primary bg-opacity-10 text-primary">${hobby}</span>`
                    );
                });

                // Check member count
                checkMemberCount($group);
            }

            // Save Changes Handler
            $saveButton.on('click', function() {
                if (!hasChanges) return;

                const groupData = {};
                $('.member-list').each(function() {
                    const groupId = $(this).data('group-id');
                    const memberIds = $(this).find('.member-item').map(function() {
                        return $(this).data('member-id');
                    }).get();
                    groupData[groupId] = memberIds
                });

                $.ajax({
                    url: '/api/update-groups',
                    method: 'POST',
                    data: JSON.stringify(groupData),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        showToast(response.message, response.status);
                        hasChanges = false;
                        $saveButton.prop('disabled', true);
                    },
                    error: function(xhr, status, error) {
                        showToast('Đã xảy ra lỗi khi lưu thay đổi. Vui lòng thử lại!',
                            'danger');
                        console.error('Error:', xhr.responseText);
                    }
                });
            });

            // Initialize member counts
            $('.member-list').each(function() {
                checkMemberCount($(this));
            });
        });
    </script>
@endpush
