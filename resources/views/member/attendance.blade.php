@extends('layouts.master')

@section('title', 'Điểm danh')

@section('content')
    <!-- Content header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Lịch học</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Lịch học</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="scheduleTable">
                        <thead>
                            <tr>
                                <th>Mã Môn</th>
                                <th>Tên Môn</th>
                                <th>Giảng Viên</th>
                                <th>Phòng Học</th>
                                <th>Ngày Học</th>
                                <th>Giờ Bắt Đầu</th>
                                <th>Giờ Kết Thúc</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lessonSchedules as $lesson)
                                <tr>
                                    <td>{{ $lesson['subject_code'] }}</td>
                                    <td>{{ $lesson['subject_name'] }}</td>
                                    <td>{{ $lesson['teacher_name'] }}</td>
                                    <td>{{ $lesson['room_name'] }}</td>
                                    <td>{{ $lesson['date'] }}</td>
                                    <td>{{ $lesson['start_hour'] }}</td>
                                    <td>{{ $lesson['end_hour'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
