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
                                    <label>Chọn môn học</label>
                                    <select class="form-control" id="subject_id" required>
                                        <option selected disabled hidden>Chọn môn học</option>
                                        @foreach ($subjects as $each)
                                            <option value="{{ $each->id }}">
                                                {{ $each->subject_code }} - {{ $each->subject_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Chọn buổi học</label>
                                    <select class="form-control" id="lesson_id" required>
                                        <option selected disabled hidden>Chọn buổi học</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Giáo viên</label>
                                    <input type="text" class="form-control" id="teacher_id" readonly>
                                </div>
                            </div>
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
            $(".datetimepicker").flatpickr({
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                minuteIncrement: 1,
                onChange: function(selectedDates, dateStr, instance) {
                    if (instance.input.id === "start_time") {
                        updateEndTime(dateStr);
                    }
                }
            });

            $("#subject_id").on("change", function() {
                $.ajax({
                    url: '/api/get-lesson-by-subject',
                    method: 'POST',
                    data: {
                        subject_id: $("#subject_id").val()
                    },
                    success: function(response) {
                        let i = 0;
                        $('#lesson_id').html(
                            '<<option selected disabled hidden>Chọn buổi học</option>');
                        response.forEach(function(element) {
                            let startTime = element.start_time.substring(0, 5);
                            let endTime = element.end_time.substring(0, 5);

                            $('#lesson_id').append(
                                `<option value="${element.id}">Buổi học ngày ${element.lesson_date}: ${startTime} => ${endTime}</option>`
                            );
                        });

                        $('#lesson_id').change(function() {
                            const selectedLesson = response.find(
                                lesson => lesson.id == $(this).val()
                            );

                            if (selectedLesson) {
                                $('#teacher_id').val(selectedLesson.teacher
                                    .display_name);
                                $("#lesson_id").data("lesson_date", selectedLesson
                                    .lesson_date);
                                $("#teacher_id").data("teacher_id", selectedLesson
                                    .teacher.id);

                                const lessonDate = selectedLesson.lesson_date;
                                const lessonStartTime = selectedLesson.start_time;

                                const formattedDate = convertDateToISOFormat(
                                    lessonDate); // yyyy-mm-dd

                                if (!lessonStartTime || lessonStartTime.length !== 8) {
                                    alert("Invalid start time format.");
                                    return;
                                }

                                const startTime = new Date(
                                    `${formattedDate}T${lessonStartTime}`);
                                startTime.setMinutes(startTime.getMinutes() - 5);

                                const formattedStartTime = startTime.toTimeString()
                                    .slice(0, 5);

                                $('#start_time').val(formattedStartTime);

                                updateEndTime(formattedStartTime);
                            }
                        });
                    },
                    error: function(xhr) {
                        alert("Có lỗi xảy ra");
                        console.log(xhr.responseText);
                    }
                });
            });

            function convertDateToISOFormat(dateStr) {
                const parts = dateStr.split('/');
                return `${parts[2]}-${parts[1]}-${parts[0]}`;
            }

            function updateEndTime(startTime) {
                const start = new Date(`1970-01-01T${startTime}:00`);
                start.setMinutes(start.getMinutes() + 10);

                const formattedEndTime = start.toTimeString().slice(0, 5);

                $('#end_time').val(formattedEndTime);
            }
            $("#use_password").change(function() {
                $("#attendance_password").prop('disabled', !this.checked);
                if (!this.checked) {
                    $("#attendance_password").val('');
                }
            });
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
