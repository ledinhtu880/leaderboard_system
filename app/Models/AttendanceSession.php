<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AttendanceSession extends Model
{
    use HasFactory;
    protected $table = 'attendance_sessions';

    protected $fillable = [
        'lesson_id',
        'teacher_id',
        'password',
        'start_time',
        'end_time',
        'is_open'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_open' => 'boolean'
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Kiểm tra xem phiên điểm danh có còn hiệu lực không
    public function isValidSession()
    {
        $now = Carbon::now();
        return $this->is_open &&
            $now->between($this->start_time, $this->end_time);
    }

    // Tạo mật khẩu ngẫu nhiên nếu không được cung cấp
    public static function generatePassword()
    {
        return str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    }
}
