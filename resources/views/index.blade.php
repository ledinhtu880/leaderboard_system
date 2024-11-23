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
                let html = '';

                for (let groupName in groups) {
                    html += `
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>${groupName.replace('_', ' ').toUpperCase()}</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                `;

                    groups[groupName].forEach(member => {
                        html += `
                        <li class="list-group-item">
                            <strong>${member.name}</strong><br>
                            GPA: ${member.gpa}<br>
                            Final Score: ${member.final_score}<br>
                            Personality: ${member.personality}
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

                $('#groupsContainer').html(html);
            }
        });
    </script>
</body>

</html>
