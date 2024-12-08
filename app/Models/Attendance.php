<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'member_id',
        'lesson_id',
        'attendance_type',
        'volunteered',
        'volunteer_points',
        'note'
    ];

    // Định nghĩa các giá trị mặc định
    protected $attributes = [
        'attendance_type' => 'absent',
        'volunteered' => false,
        'volunteer_points' => 0
    ];

    // Quan hệ với Lesson
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    // Quan hệ với Member
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    // Scope để lọc các bản ghi điểm danh
    public function scopePresent($query)
    {
        return $query->where('attendance_type', 'present');
    }

    public function scopeAbsent($query)
    {
        return $query->where('attendance_type', 'absent');
    }

    public function scopeVolunteered($query)
    {
        return $query->where('volunteered', true);
    }

    // Accessor để format điểm danh
    public function getAttendanceStatusAttribute()
    {
        return match ($this->attendance_type) {
            'present' => 'Có mặt',
            'absent' => 'Vắng mặt',
            'late' => 'Đến muộn',
            'excused' => 'Vắng có phép',
            default => 'Không xác định'
        };
    }
}
