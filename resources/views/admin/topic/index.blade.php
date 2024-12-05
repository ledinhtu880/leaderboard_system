@extends('layouts.master')

@section('title', 'Danh sách đề tài')

@section('content')
    <div id="cover-spin"></div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Danh sách đề tài</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Danh sách đề tài</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card shadow">
                <div class="card-body">
                    <table class="table" id="topicsTable">
                        <thead>
                            <tr class="align-middle">
                                <th></th>
                                <th>Tên đề tài</th>
                                <th>Mô tả</th>
                                <th class="text-center">Số thành viên</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topics as $topic)
                                <tr class="align-middle">
                                    <td class="text-center">
                                        <i class="fas fa-plus-circle text-primary expand-details" style="cursor: pointer;"
                                            data-topic="{{ $topic->id }}"></i>
                                    </td>
                                    <td class="fw-bold text-primary"
                                        style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                        title="{{ $topic->name }}">
                                        {{ $topic->name }}
                                    </td>
                                    <td style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                        title="{{ $topic->description }}">
                                        {{ $topic->description }}
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge {{ $topic->members->count() >= 5 ? 'bg-danger' : 'bg-success' }}">
                                            {{ $topic->members->count() }}/5
                                        </span>
                                    </td>
                                </tr>
                                <tr class="member-details-{{ $topic->id }}" style="display: none;">
                                    <td colspan="5">
                                        <div class="list-group mx-4 my-2">
                                            @foreach ($topic->members as $memberTopic)
                                                <div class="list-group-item"
                                                    data-member-id="{{ $memberTopic->member->id }}">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <div>
                                                            <span class="h6">
                                                                <i class="fas fa-user-circle me-2"></i>
                                                                {{ $memberTopic->member->name }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <span class="badge bg-info me-2">
                                                                GPA: {{ $memberTopic->member->gpa }}
                                                            </span>
                                                            <span class="badge bg-secondary">
                                                                GPA kỳ trước: {{ $memberTopic->member->last_gpa }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <div class="card border-0 bg-light">
                                                                <div class="card-body p-2 text-center">
                                                                    <div class="small text-muted mb-1">Môn học 1</div>
                                                                    <div
                                                                        class="h5 mb-0 {{ $memberTopic->member->subject_1_mark >= 7 ? 'text-success' : ($memberTopic->member->subject_1_mark >= 5 ? 'text-warning' : 'text-danger') }}">
                                                                        {{ $memberTopic->member->subject_1_mark }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="card border-0 bg-light">
                                                                <div class="card-body p-2 text-center">
                                                                    <div class="small text-muted mb-1">Môn học 2</div>
                                                                    <div
                                                                        class="h5 mb-0 {{ $memberTopic->member->subject_2_mark >= 7 ? 'text-success' : ($memberTopic->member->subject_2_mark >= 5 ? 'text-warning' : 'text-danger') }}">
                                                                        {{ $memberTopic->member->subject_2_mark }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="card border-0 bg-light">
                                                                <div class="card-body p-2 text-center">
                                                                    <div class="small text-muted mb-1">Môn học 3</div>
                                                                    <div
                                                                        class="h5 mb-0 {{ $memberTopic->member->subject_3_mark >= 7 ? 'text-success' : ($memberTopic->member->subject_3_mark >= 5 ? 'text-warning' : 'text-danger') }}">
                                                                        {{ $memberTopic->member->subject_3_mark }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12 d-flex justify-content-end align-items-center gap-2">
                    <button class="btn btn-secondary" id="btnArrange">Sắp xếp lại chủ đề</button>
                    <button class="btn btn-primary" id="btnSave">Lưu lại</button>
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
            $('#btnArrange').click(function() {
                $(this).prop('disabled', true).text('Đang sắp xếp...');
                $("#cover-spin").show();

                $.ajax({
                    url: "/api/run_clustering",
                    method: 'POST',
                    success: function(response) {
                        $('#topicsTable tbody').empty();

                        Object.keys(response.topic_groups).forEach(function(topicId) {
                            const topicData = response.topic_groups[topicId];
                            const topicMembers = topicData.members;

                            const topicRow = `
            <tr class="align-middle">
                <td class="text-center">
                    <i class="fas fa-plus-circle text-primary expand-details" style="cursor: pointer;"
                        data-topic="${topicId}"></i>
                </td>
                <td class="fw-bold text-primary" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${topicData.name}">
                    ${topicData.name}
                </td>
                <td style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${topicData.description}">
                    ${topicData.description}
                </td>
                <td class="text-center">
                    <span class="badge ${topicMembers.length >= 5 ? 'bg-danger' : 'bg-success'}">
                        ${topicMembers.length}/5
                    </span>
                </td>
            </tr>
        `;

                            const memberDetailsRow = `
            <tr class="member-details-${topicId}" style="display: none;">
                <td colspan="5">
                    <div class="list-group mx-4 my-2">
                        ${topicMembers.map(member => `
                                                                                                                                                <div class="list-group-item" data-member-id=${member.id}>
                                                                                                                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                                                                                                                        <div>
                                                                                                                                                            <span class="h6">
                                                                                                                                                                <i class="fas fa-user-circle me-2"></i>
                                                                                                                                                                ${member.name}
                                                                                                                                                            </span>
                                                                                                                                                        </div>
                                                                                                                                                        <div>
                                                                                                                                                            <span class="badge bg-info me-2">
                                                                                                                                                                GPA: ${member.gpa}
                                                                                                                                                            </span>
                                                                                                                                                            <span class="badge bg-secondary">
                                                                                                                                                                GPA kỳ trước: ${member.last_gpa}
                                                                                                                                                            </span>
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                    <div class="row g-3">
                                                                                                                                                        <div class="col-md-4">
                                                                                                                                                            <div class="card border-0 bg-light">
                                                                                                                                                                <div class="card-body p-2 text-center">
                                                                                                                                                                    <div class="small text-muted mb-1">Môn học 1</div>
                                                                                                                                                                    <div class="h5 mb-0 ${member.subject_1_mark >= 7 ? 'text-success' : (member.subject_1_mark >= 5 ? 'text-warning' : 'text-danger')}">
                                                                                                                                                                        ${member.subject_1_mark}
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                        <div class="col-md-4">
                                                                                                                                                            <div class="card border-0 bg-light">
                                                                                                                                                                <div class="card-body p-2 text-center">
                                                                                                                                                                    <div class="small text-muted mb-1">Môn học 2</div>
                                                                                                                                                                    <div class="h5 mb-0 ${member.subject_2_mark >= 7 ? 'text-success' : (member.subject_2_mark >= 5 ? 'text-warning' : 'text-danger')}">
                                                                                                                                                                        ${member.subject_2_mark}
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                        <div class="col-md-4">
                                                                                                                                                            <div class="card border-0 bg-light">
                                                                                                                                                                <div class="card-body p-2 text-center">
                                                                                                                                                                    <div class="small text-muted mb-1">Môn học 3</div>
                                                                                                                                                                    <div class="h5 mb-0 ${member.subject_3_mark >= 7 ? 'text-success' : (member.subject_3_mark >= 5 ? 'text-warning' : 'text-danger')}">
                                                                                                                                                                        ${member.subject_3_mark}
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                            `).join('')}
                    </div>
                </td>
            </tr>
        `;

                            $('#topicsTable tbody').append(topicRow);
                            $('#topicsTable tbody').append(memberDetailsRow);
                        });

                        $('#btnArrange').prop('disabled', false).text('Sắp xếp lại chủ đề');
                    },
                    error: function() {
                        alert('Đã xảy ra lỗi khi sắp xếp nhóm.');
                        $('#btnArrange').prop('disabled', false).text('Sắp xếp lại chủ đề');
                    },
                    complete: function() {
                        $("#cover-spin").hide();
                    }
                });
            });

            // Mở rộng chi tiết thành viên
            $(document).on('click', '.expand-details', function() {
                const topicId = $(this).data('topic');
                $(this).toggleClass('fa-plus-circle fa-minus-circle');
                $(`.member-details-${topicId}`).toggle();
            });

            $('#btnSave').on('click', function() {
                const topicGroups = {};

                // Collect topic data from current table state
                $('#topicsTable tbody tr.align-middle').each(function() {
                    const topicId = $(this).find('.expand-details').data('topic');

                    const members = [];
                    $(`.member-details-${topicId} .list-group-item`).each(function() {
                        const memberId = $(this).data('member-id');
                        members.push(memberId);
                    });

                    topicGroups[topicId] = {
                        members: members
                    };
                });

                $.ajax({
                    url: '/api/update_topics', // Your endpoint
                    type: 'POST',
                    data: {
                        topic_groups: topicGroups,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        showToast(response.message, response.status);
                    },
                    error: function(xhr) {
                        alert("Đã có lỗi xảy ra trong quá trình lưu nhóm!");
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endpush
