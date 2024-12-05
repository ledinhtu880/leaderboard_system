@extends('layouts.master')

@section('title', 'Danh sách đề tài')

@section('content')
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
        {{-- <div class="container-fluid">
            <div class="row">
                @foreach ($topics as $topic)
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm hover-shadow">
                            <div class="card-header bg-gradient-light d-flex justify-content-between align-items-center">
                                <h3 class="card-title mb-0 text-primary">
                                    <i class="fas fa-bookmark me-2"></i>{{ $topic->name }}
                                </h3>
                                <span
                                    class="badge {{ $topic->members->count() >= 5 ? 'bg-danger' : 'bg-success' }} px-3 py-2">
                                    <i class="fas fa-users me-1"></i>{{ $topic->members->count() }}/5
                                </span>
                            </div>
                            <div class="card-body">
                                <p class="card-text text-muted">
                                    <i class="fas fa-info-circle me-2"></i>{{ $topic->description }}
                                </p>
                                <h5 class="mt-4 mb-3 text-secondary">
                                    <i class="fas fa-user-friends me-2"></i>Thành viên
                                </h5>
                                <div class="list-group">
                                    @foreach ($topic->members as $memberTopic)
                                        <div
                                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-user-circle me-2 text-primary"></i>
                                                {{ $memberTopic->member->name }}
                                            </div>
                                            <button class="btn btn-outline-success btn-sm rounded-pill">
                                                <i class="fas fa-check me-1"></i>Duyệt
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="card-footer bg-light d-flex justify-content-end">
                                <button class="btn btn-primary btn-sm rounded-pill">
                                    <i class="fas fa-cog me-1"></i>Quản lý
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div> --}}
        <div class="container-fluid">
            <div class="card shadow">
                <div class="card-body">
                    <table class="table table-hover" id="topicsTable">
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
                                                <div
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>
                                                        <i class="fas fa-user-circle me-2"></i>
                                                        {{ $memberTopic->member->name }}
                                                    </span>
                                                    {{-- <button class="btn btn-outline-success btn-sm">
                                                        <i class="fas fa-check me-1"></i>Duyệt
                                                    </button> --}}
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

    {{-- <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.list-group').each(function() {
                new Sortable(this, {
                    group: 'shared',
                    animation: 150,
                    onEnd: function(evt) {
                        const memberId = $(evt.item).find('.fas').parent().text().trim();
                        const newTopicRow = $(evt.to).closest('tr').prev();
                        const oldTopicRow = $(evt.from).closest('tr').prev();

                        updateMemberCount(newTopicRow);
                        updateMemberCount(oldTopicRow);
                    }
                });
            });

            function updateMemberCount($topicRow) {
                const $membersList = $topicRow.next().find('.list-group');
                const count = $membersList.children().length;
                const $badge = $topicRow.find('.badge');
                $badge.text(`${count}/5`);
                $badge.removeClass('bg-success bg-danger');
                $badge.addClass(count >= 5 ? 'bg-danger' : 'bg-success');
            }
        });
    </script> --}}
    <script>
        $(document).ready(function() {
            $('#btnArrange').click(function() {
                // Hiển thị trạng thái đang xử lý
                $(this).prop('disabled', true).text('Đang sắp xếp...');

                // Gửi yêu cầu tới APIController
                $.ajax({
                    url: "{{ route('api.runClustering') }}", // Route của hàm runClustering()
                    method: 'GET',
                    success: function(response) {
                        // Xóa dữ liệu cũ
                        $('#topicsTable tbody').empty();

                        // Duyệt qua các nhóm mới sắp xếp
                        Object.keys(response.topic_groups).forEach(function(topicId) {
                            const topicMembers = response.topic_groups[topicId];

                            // Tạo dòng chính cho mỗi nhóm
                            const topicRow = `
                                <tr class="align-middle">
                                    <td class="text-center">
                                        <i class="fas fa-plus-circle text-primary expand-details" style="cursor: pointer;"
                                            data-topic="${topicId}"></i>
                                    </td>
                                    <td class="fw-bold text-primary" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="Nhóm ${topicId}">
                                        Nhóm ${topicId}
                                    </td>
                                    <td style="max-width: 300px;">-</td>
                                    <td class="text-center">
                                        <span class="badge ${topicMembers.length >= 5 ? 'bg-danger' : 'bg-success'}">
                                            ${topicMembers.length}/5
                                        </span>
                                    </td>
                                </tr>
                            `;

                            // Tạo dòng chi tiết thành viên
                            const memberDetailsRow = `
                                <tr class="member-details-${topicId}" style="display: none;">
                                    <td colspan="5">
                                        <div class="list-group mx-4 my-2">
                                            ${topicMembers.map(member => `
                                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                                        <span><i class="fas fa-user-circle me-2"></i>${member.name}</span>
                                                        <button class="btn btn-outline-success btn-sm">
                                                            <i class="fas fa-check me-1"></i>Duyệt
                                                        </button>
                                                    </div>
                                                `).join('')}
                                        </div>
                                    </td>
                                </tr>
                            `;

                            // Thêm vào bảng
                            $('#topicsTable tbody').append(topicRow);
                            $('#topicsTable tbody').append(memberDetailsRow);
                        });

                        // Cập nhật trạng thái nút
                        $('#btnArrange').prop('disabled', false).text('Sắp xếp lại chủ đề');
                    },
                    error: function() {
                        alert('Đã xảy ra lỗi khi sắp xếp nhóm.');
                        $('#btnArrange').prop('disabled', false).text('Sắp xếp lại chủ đề');
                    }
                });
            });

            // Mở rộng chi tiết thành viên
            $(document).on('click', '.expand-details', function() {
                const topicId = $(this).data('topic');
                $(this).toggleClass('fa-plus-circle fa-minus-circle');
                $(`.member-details-${topicId}`).toggle();
            });
        });
    </script>
@endpush
