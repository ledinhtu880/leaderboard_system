@extends('layouts.master')

@section('title', 'Chạy thuật toán phân cụm')

@section('content')
    <div class="container">
        <h2>Phân chia nhóm</h2>

        <button id="createGroupsBtn" class="btn btn-primary">
            Tạo nhóm
        </button>

        <div id="loadingSpinner" style="display: none;">
            Đang xử lý...
        </div>

        <div id="groupsResult" class="mt-4">
            <div class="row" id="groupsContainer">
                <!-- Kết quả sẽ được hiển thị ở đây -->
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
                $('#loadingSpinner').show();
                $('#groupsContainer').empty();

                $.ajax({
                    url: '/run_cluster',
                    method: 'GET',
                    success: function(response) {
                        $('#loadingSpinner').hide();
                        if (response.error) {
                            alert('Lỗi: ' + response.error);
                            return;
                        }
                        displayGroups(response);
                    },
                    error: function(xhr) {
                        $('#loadingSpinner').hide();
                        let errorMessage = 'Có lỗi xảy ra khi tạo nhóm';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage += ': ' + xhr.responseJSON.error;
                        }
                        alert(errorMessage);
                    },
                    complete: function() {
                        $("#cover-spin").hide();
                        showToast("Phân nhóm thành công", "success");
                    }
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
                                                <div><i class="fas fa-chart-line"></i> GPA TB: ${stats.avg_gpa}</div>
                                                <div><i class="fas fa-star"></i> Điểm TB: ${stats.avg_final_score}</div>
                                                <div class="text-truncate">
                                                    <i class="fas fa-heart"></i> Sở thích: ${stats.hobbies.join(', ') || 'Chưa có'}
                                                </div>
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
                        const finalScore = member.final_score !== 'N/A' ?
                            parseFloat(member.final_score)
                            .toFixed(2) :
                            'N/A';

                        // Xử lý hiển thị sở thích
                        const hobby = member.hobby === 'N/A' ? 'Chưa có' : member.hobby;

                        // Tạo màu badge dựa trên personality
                        const personalityColor = getPersonalityColor(member.personality);

                        html += `<li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <strong>${member.name}</strong>
                                        <span class="badge ${personalityColor}">${member.personality == 0 ? "Hướng nội" : "Hướng ngoại"}</span>
                                    </div>
                                    <div class="small">
                                        <div class="row">
                                            <div class="col-6" title="GPA Hiện tại">
                                                <i class="fas fa-graduation-cap"></i> GPA: ${gpa}
                                            </div>
                                            <div class="col-6" title="GPA Kỳ trước">
                                                <i class="fas fa-history"></i> Last: ${lastGpa}
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-6" title="Điểm tổng kết">
                                                <i class="fas fa-star"></i> Final: ${finalScore}
                                            </div>
                                            <div class="col-6 text-truncate" title="${hobby}">
                                                <i class="fas fa-heart"></i> ${hobby}
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

            // Hàm helper để xác định màu cho personality
            function getPersonalityColor(personality) {
                const colors = {
                    '0': 'bg-info',
                    '1': 'bg-primary',
                };

                return colors[personality] || 'bg-secondary';
            }
        });
    </script>
@endpush
