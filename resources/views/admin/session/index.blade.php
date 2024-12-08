@extends('layouts.master')

@section('title', 'Tạo phiên điểm danh')

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('content')
    <!-- Content header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tạo phiên điểm danh</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Tạo phiên điểm danh</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Thông tin phiên điểm danh</h3>
                </div>
                <form id="attendanceSessionForm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Chọn môn học</label>
                                    <select class="form-control" id="lesson_id" required>
                                        <option value="">Chọn môn học</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Giáo viên</label>
                                    <input type="text" class="form-control" id="teacher_id" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Thời gian bắt đầu</label>
                                    <input type="text" class="form-control datetimepicker" id="start_time" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Thời gian kết thúc</label>
                                    <input type="text" class="form-control datetimepicker" id="end_time" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" id="use_password">
                                        Sử dụng mật khẩu cho phiên điểm danh
                                    </label>
                                    <input type="text" class="form-control" id="attendance_password"
                                        placeholder="Nhập mật khẩu" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i> Mở phiên điểm danh
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"
        integrity="sha512-K/oyQtMXpxI4+K0W7H25UopjM8pzq0yrVdFdG21Fh5dBe91I40pDd9A4lzNlHPHBIP2cwZuoxaUSX0GJSObvGA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            // Khởi tạo flatpickr cho start_time và end_time
            $(".datetimepicker").flatpickr({
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i", // Hiển thị giờ và phút
                time_24hr: true,
                minuteIncrement: 1,
                onChange: function(selectedDates, dateStr, instance) {
                    // Khi thay đổi start_time, cập nhật end_time
                    if (instance.input.id === "start_time") {
                        updateEndTime(dateStr);
                    }
                }
            });

            loadLessonsAndTeachers();

            function loadLessonsAndTeachers() {
                $.ajax({
                    url: '/api/get-lessons', // Endpoint API của bạn
                    method: 'GET',
                    success: function(data) {
                        $('#lesson_id').html('<option value="">Chọn môn học</option>');
                        data.lessons.forEach(function(lesson) {
                            $('#lesson_id').append(`
                                        <option value="${lesson.id}">
                                            ${lesson.subject.subject_name} - ${lesson.lesson_date}
                                        </option>
                                    `);
                        });

                        // Sự kiện chọn môn học
                        $('#lesson_id').change(function() {
                            const selectedLesson = data.lessons.find(
                                lesson => lesson.id == $(this).val()
                            );

                            if (selectedLesson) {
                                $("#lesson_id").data("lesson_date", selectedLesson.lesson_date);
                                $('#teacher_id').val(selectedLesson.teacher.display_name);
                                $("#teacher_id").data("teacher_id", selectedLesson.teacher.id);

                                // Lấy lesson_date và start_time của Lesson
                                const lessonDate = selectedLesson
                                    .lesson_date; // Định dạng dd/mm/yyyy
                                const lessonStartTime = selectedLesson
                                    .start_time; // Định dạng HH:mm:ss

                                // Chuyển đổi lesson_date từ dd/mm/yyyy thành yyyy-mm-dd
                                const formattedDate = convertDateToISOFormat(
                                    lessonDate); // yyyy-mm-dd

                                // Kiểm tra xem lessonStartTime có hợp lệ không
                                if (!lessonStartTime || lessonStartTime.length !== 8) {
                                    alert("Invalid start time format.");
                                    return;
                                }

                                const startTime = new Date(
                                    `${formattedDate}T${lessonStartTime}`);
                                startTime.setMinutes(startTime.getMinutes() - 5);

                                // Định dạng lại giờ phút
                                const formattedStartTime = startTime.toTimeString().slice(0, 5);

                                // Gán vào ô "thời gian bắt đầu"
                                $('#start_time').val(formattedStartTime);

                                // Cập nhật end_time là 10 phút sau start_time
                                updateEndTime(formattedStartTime);
                            }
                        });
                    }
                });
            }

            // Hàm chuyển đổi định dạng dd/mm/yyyy thành yyyy-mm-dd
            function convertDateToISOFormat(dateStr) {
                const parts = dateStr.split('/');
                return `${parts[2]}-${parts[1]}-${parts[0]}`; // yyyy-mm-dd
            }

            // Hàm cập nhật end_time
            function updateEndTime(startTime) {
                const start = new Date(`1970-01-01T${startTime}:00`); // Thêm ":00" để có giây
                start.setMinutes(start.getMinutes() + 10); // Thêm 10 phút

                const formattedEndTime = start.toTimeString().slice(0, 5); // Định dạng HH:mm

                // Cập nhật vào input end_time
                $('#end_time').val(formattedEndTime);
            }
            $("#use_password").change(function() {
                $("#attendance_password").prop('disabled', !this.checked);
                if (!this.checked) {
                    $("#attendance_password").val('');
                }
            });
            // Gửi form mở phiên điểm danh
            $('#attendanceSessionForm').on("submit", function(e) {
                e.preventDefault();

                const formData = {
                    lesson_id: $('#lesson_id').val(),
                    start_time: $('#start_time').val(),
                    end_time: $('#end_time').val(),
                    password: $('#use_password').is(':checked') ?
                        $('#attendance_password').val() : null,
                    teacher_id: $("#teacher_id").data("teacher_id"),
                    lesson_date: $("#lesson_id").data("lesson_date")
                };
                $.ajax({
                    url: '/api/store-session',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        showToast(response.message, response.status);
                    },
                    error: function(xhr) {
                        alert("Có lỗi xảy ra!");
                        console.log(xhr.responseText)
                    }
                });
            });
        });
    </script>
@endpush
