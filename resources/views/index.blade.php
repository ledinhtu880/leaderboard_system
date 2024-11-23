<!DOCTYPE html>
<html>

<head>
    <title>Phân chia nhóm</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container mt-5">
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

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#createGroupsBtn').click(function() {
                $('#loadingSpinner').show();
                $('#groupsContainer').empty();

                $.ajax({
                    url: '/create-groups',
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
                    }
                });
            });

            function displayGroups(groups) {
                let html = '<div class="row">';

                for (let groupName in groups) {
                    const groupData = groups[groupName];
                    const stats = groupData.stats;

                    html += `
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">${groupName.replace('_', ' ').toUpperCase()}</h5>
                    </div>
                    <div class="card-body p-0">
                        <!-- Group Statistics -->
                        <div class="bg-light p-3 border-bottom">
                            <h6 class="mb-2">Thống kê nhóm:</h6>
                            <small>
                                <div>GPA TB: ${stats.avg_gpa.toFixed(2)}</div>
                                <div>Điểm TB: ${stats.avg_final_score.toFixed(2)}</div>
                                <div>Sở thích: ${stats.hobbies.join(', ')}</div>
                            </small>
                        </div>
                        
                        <!-- Group Members -->
                        <ul class="list-group list-group-flush">
            `;

                    groupData.members.forEach(member => {
                        html += `
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <strong>${member.name}</strong>
                            <span class="badge bg-info">${member.personality}</span>
                        </div>
                        <div class="small">
                            <div class="row">
                                <div class="col-6">GPA: ${member.gpa}</div>
                                <div class="col-6">Last: ${member.last_gpa}</div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-6">Final: ${member.final_score}</div>
                                <div class="col-6">Hobby: ${member.hobby}</div>
                            </div>
                        </div>
                    </li>
                `;
                    });

                    html += `
                        </ul>
                    </div>
                </div>
            </div>
            `;
                }

                html += '</div>';
                $('#groupsContainer').html(html);

                // Thêm tooltip cho các thẻ có class 'hobby'
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    </script>
</body>

</html>
