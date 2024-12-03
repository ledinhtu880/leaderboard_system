<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Chọn Chủ Đề Dự Án</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .topic-card {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .topic-card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .topic-card.selected {
            border: 2px solid #007bff;
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Chọn Chủ Đề Dự Án</h3>
            </div>
            <div class="card-body">
                <form id="topicRegistrationForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card topic-card" data-topic-id="1">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Hệ thống kinh doanh thông minh cho nông nghiệp</h5>
                                    <p class="card-text">Phát triển một ứng dụng di động giúp nông dân quản lý mùa vụ,
                                        theo dõi tình trạng cây trồng và cung cấp dự báo thời tiết dựa trên dữ liệu lớn
                                        và trí tuệ nhân tạo, từ đó tối ưu hóa quy trình sản xuất</p>
                                    <input type="radio" name="topic_id" value="1" class="d-none topic-radio">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card topic-card" data-topic-id="2">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Hệ thống quản lý tài chính thông minh cho cá nhân</h5>
                                    <p class="card-text">Xây dựng một ứng dụng quản lý chi tiêu cá nhân có tính năng
                                        phân tích tài chính, lập kế hoạch ngân sách tự động và nhắc nhờ thanh toán hóa
                                        đơn để giiups người theo dõi và cải thiện tình hình tài chính của mình</p>
                                    <input type="radio" name="topic_id" value="2" class="d-none topic-radio">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card topic-card" data-topic-id="3">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Hệ thống tìm kiếm mặt bằng cho thuê thông minh</h5>
                                    <p class="card-text">Tạo một nền tảng trực tuyến cho phép người dùng tìm kiếm mặt
                                        bằng cho thuê với các bộ lọc thông minh như vị trí, giá cả, diện tích và loại
                                        hình kinh doanh, đồng thời tích hợp đánh giá từ người thuê trước đó</p>
                                    <input type="radio" name="topic_id" value="3" class="d-none topic-radio">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card topic-card" data-topic-id="4">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Hệ thống đào tạo thông minh cho sinh viên</h5>
                                    <p class="card-text">Phát triển một nền tảng học trực tuyến sử dụng nhân tạo để cá
                                        nhân hóa lộ trình học tập cho sinh viên, cung cấp tài liệu học tập phù hợp và
                                        theo dõi tiến độ học tập của người dùng</p>
                                    <input type="radio" name="topic_id" value="4" class="d-none topic-radio">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success mt-3" disabled id="submitBtn">Xác Nhận Chủ Đề</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.topic-card').on('click', function() {
                $('.topic-card').removeClass('selected');
                $(this).addClass('selected');
                $(this).find('.topic-radio').prop('checked', true);
                $('#submitBtn').prop('disabled', false);
            });

            $('#topicRegistrationForm').on('submit', function(e) {
                e.preventDefault();

                const topicId = $('input[name="topic_id"]:checked').val();

                $.ajax({
                    url: '/register-topic',
                    method: 'POST',
                    data: {
                        topic_id: topicId
                    },
                    success: function(response) {
                        alert('Đăng ký chủ đề thành công!');
                    },
                    error: function() {
                        alert('Có lỗi xảy ra. Vui lòng thử lại.');
                    }
                });
            });
        });
    </script>
</body>

</html>
