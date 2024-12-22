<tr>
    <td class="text-center">{{ $each['STT'] }}</td>
    <td>{{ $each['Họ'] . ' ' . $each['Tên'] }}</td>
    <td>{{ $each['Mã sinh viên'] }}</td>
    <td>{{ $each['Lớp'] }}</td>
    <td>
        {{ floor($each['Điểm project']) == $each['Điểm project'] ? number_format($each['Điểm project'], 0) : number_format($each['Điểm project'], 1, '.', '') }}
    </td>
    <td>{{ $each['Phát biểu'] }}</td>
    <td>{{ $each['Vắng'] }}</td>
    <td class="text-center">
        <button type="button" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $each['STT'] }}"
            class="btn btn-sm btn-outline-primary">
            <i class="fa-solid fa-eye"></i>
        </button>
        <div class="modal fade" id="modalDetail{{ $each['STT'] }}" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="modalDetailLabel{{ $each['STT'] }}" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalDetailLabel{{ $each['STT'] }}">
                            {{ $each['Họ'] . ' ' . $each['Tên'] }}
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="info-box">
                                    <span class="info-box-icon bg-olive"><i class="fas fa-user-clock"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Điểm chuyên cần</span>
                                        <span class="info-box-number">{{ $each['Điểm chuyên cần'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <span class="info-box-icon bg-teal"><i class="far fa-comment-dots"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Điểm phát biểu</span>
                                        <span class="info-box-number">{{ $each['Điểm phát biểu'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success"><i class="fas fa-chart-bar"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Điểm tổng</span>
                                        <span class="info-box-number">{{ $each['Điểm tổng'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <span class="info-box-icon bg-lightblue"><i class="fas fa-user-graduate"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">GPA (Hệ 4)</span>
                                        <span class="info-box-number">
                                            @if (session('msv') == $each['Mã sinh viên'])
                                                {{ session('gpa4') }}
                                            @else
                                                {{ !array_key_exists('gpa4', $each) || is_null($each['gpa4']) ? 'Chưa có thông tin' : $each['gpa4'] }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <span class="info-box-icon bg-primary"><i class="fas fa-graduation-cap"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Dự đoán điểm thi cuối kỳ</span>
                                        <span class="info-box-number">
                                            @if (session('msv') == $each['Mã sinh viên'])
                                                {{ session('gpa10') }}
                                            @else
                                                {{ !array_key_exists('gpa10', $each) || is_null($each['gpa10']) ? 'Chưa có thông tin' : $each['gpa10'] }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <span class="info-box-icon bg-primary"><i class="fas fa-medal"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Ranking</span>
                                        <span class="info-box-number">
                                            {{ $each['ranking'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm attendance-table">
                                        @php
                                            $dates = [
                                                '12/11',
                                                '15/11',
                                                '19/11',
                                                '22/11',
                                                '26/11',
                                                '29/11',
                                                '03/12',
                                                '06/12',
                                                '10/12',
                                                '13/12',
                                                '17/12',
                                                '20/12',
                                                '24/12',
                                                '27/12',
                                                '03/01',
                                            ];
                                            $absences = explode(',', $each['Những ngày vắng']);
                                            $participations = explode(',', $each['Những ngày phát biểu']);
                                        @endphp
                                        <thead>
                                            <tr>
                                                @foreach ($dates as $date)
                                                    <th @if ($date == \Carbon\Carbon::today()->format('d/m')) class="current-day" @endif>
                                                        {{ $date }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                @foreach ($dates as $date)
                                                    <td class="text-center">
                                                        @if (in_array($date, $absences))
                                                            <span title="Vắng mặt ngày {{ $date }}">Vắng</span>
                                                        @endif
                                                        @if (in_array($date, $participations))
                                                            <span title="Phát biểu ngày {{ $date }}">Phát
                                                                biểu</span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </td>
</tr>
