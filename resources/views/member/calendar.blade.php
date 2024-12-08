@extends('layouts.master')

@section('title', 'Trang chủ')


@push('css')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.css' rel='stylesheet' />
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.print.min.css' rel='stylesheet'
        media='print' />
    <style>
        .fc-time-grid .fc-slats td {
            height: 40px;
            /* Điều chỉnh giá trị này để thay đổi chiều cao của các ô */
        }
    </style>
@endpush

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
            <div id="calendar"></div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@push('js')
    <script src='https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/locale/vi.js'></script>
    <script>
        $(document).ready(function() {
            var events = [
                @foreach ($lessons as $lesson)
                    {
                        title: '{{ $lesson->subject_name }} \n {{ $lesson->room_name }}',
                        start: '{{ $lesson->lesson_date }} {{ $lesson->start_time }}',
                        end: '{{ $lesson->lesson_date }} {{ $lesson->end_time }}',
                        dow: [{{ $lesson->week_index == 3 ? 2 : 5 }}]
                    },
                @endforeach
            ];

            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'agendaWeek,agendaDay'
                },
                defaultView: 'agendaWeek',
                minTime: "07:00:00", // Bắt đầu từ 7:00
                maxTime: "19:00:00", // Kết thúc tại 19:00,
                allDaySlot: false, // Bỏ dòng sự kiện cả ngày
                columnHeaderFormat: 'dddd',
                titleFormat: 'D/MM/YYYY',
                events: events,
                locale: 'h',
            });
        });
    </script>
@endpush
