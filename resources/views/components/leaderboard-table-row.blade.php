@props(['member'])

@php
    $bgColors = [
        1 => '#FCFFC1',
        2 => '#F2F9FF',
        3 => '#FFF0DC',
    ];

    $rankClasses = [
        1 => 'r-1',
        2 => 'r-2',
        3 => 'r-3',
    ];

    $backgroundColor = $member['Vắng'] > 3 ? '#F95454' : $bgColors[$member['ranking']] ?? 'transparent';
    $rankClass = $rankClasses[$member['ranking']] ?? '';
@endphp

<tr>
    <td style="background-color: {{ $backgroundColor }} !important;"
        class="d-flex align-items-center justify-content-center">
        <span class="rank-badge {{ $rankClass }}">{{ $member['ranking'] }}</span>
    </td>
    <td style="background-color: {{ $backgroundColor }} !important;">
        {{ $member['Họ'] . ' ' . $member['Tên'] }}
    </td>
    <td style="background-color: {{ $backgroundColor }} !important;">
        {{ $member['Mã sinh viên'] }}
    </td>
    <td style="background-color: {{ $backgroundColor }} !important;">
        {{ $member['Lớp'] }}
    </td>
    <td style="background-color: {{ $backgroundColor }} !important;">
        {{ $member['Điểm project'] }}
    </td>
    <td style="background-color: {{ $backgroundColor }} !important;">
        {{ $member['Điểm chuyên cần'] }}
    </td>
    <td style="background-color: {{ $backgroundColor }} !important;">
        {{ $member['Điểm phát biểu'] }}
    </td>
    <td style="background-color: {{ $backgroundColor }} !important;">
        {{ $member['Điểm tổng'] }}
    </td>
</tr>
