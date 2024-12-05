@extends('layouts.master')

@section('title', 'Chạy thuật toán phân cụm')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Phân chia nhóm</h2>
            <button class="btn btn-dark" id="createGroupsBtn">
                <i class="fas fa-plus me-2"></i>Chạy thuật toán
            </button>
        </div>

        <div id="groupsResult" class="mt-4">
            <div class="row" id="groupsContainer">
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#createGroupsBtn').click(function() {
                $("#cover-spin").show();
                $('#groupsContainer').empty();

                $.ajax({
                    url: '/api/run_clustering',
                    method: 'GET',
                    success: function(response) {
                        $("#cover-spin").hide();
                        if (response.error) {
                            alert('Lỗi: ' + response.error);
                            return;
                        }
                        showToast("Phân nhóm thành công", "success");
                        displayGroups(response);
                        $("#createGroupsBtn").prop('disabled', true);
                    },
                    error: function(xhr) {
                        $("#cover-spin").hide();
                        let errorMessage = 'Có lỗi xảy ra khi tạo nhóm';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage += ': ' + xhr.responseJSON.error;
                        }
                        alert(errorMessage);
                    },
                });
            });

            function displayGroups(data) {
                if (data.error) {
                    $('#groupsContainer').html(`<div class="alert alert-danger">${data.error}</div>`);
                    return;
                }

                const groups = data;
                let html = '<div class="row">';

                for (let groupName in groups) {
                    const groupData = groups[groupName];
                    const stats = groupData.stats;

                    html += `<div class="col-md-3 mb-4">
                                <div class="card h-100">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">${groupName.replace('_', ' ').toUpperCase()}</h5>
                                    </div>
                                    <div class="card-body p-0">
                                        <!-- Group Statistics -->
                                        <div class="bg-light p-3 border-bottom">
                                            <h6 class="mb-2">Thống kê nhóm:</h6>
                                            <small>
                                                <div><i class="fas fa-chart-line text-info"></i> GPA TB: ${stats.avg_gpa}</div>
                                            </small>
                                        </div>
                                        <!-- Members List -->
                                        <div class="members-list" style="max-height: 400px; overflow-y: auto;">
                                            <ul class="list-group list-group-flush">`;

                    groupData.members.forEach(member => {
                        // Xử lý hiển thị GPA và điểm
                        const gpa = member.gpa !== 'N/A' ?
                            parseFloat(member.gpa).toFixed(2) :
                            'N/A';
                        const lastGpa = member.last_gpa !== 'N/A' ?
                            parseFloat(member.last_gpa).toFixed(2) :
                            'N/A';
                        html += `<li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <strong>${member.name}</strong>
                                    </div>
                                    <div class="small">
                                        <div class="row">
                                            <div class="col-6" title="GPA Hiện tại">
                                                <i class="fas fa-graduation-cap text-primary"></i> GPA: ${gpa}
                                            </div>
                                            <div class="col-6" title="GPA Kỳ trước">
                                                <i class="fas fa-history text-info"></i> Last: ${lastGpa}
                                            </div>
                                        </div>
                                    </div>
                                </li>`;
                    });

                    html += `</ul>
                                </div>
                            </div>
                        </div>
                    </div>`;
                }

                html += '</div>';
                $('#groupsContainer').html(html);

                // Khởi tạo tooltips
                $('[title]').tooltip();
            }
        });
    </script>
@endpush
