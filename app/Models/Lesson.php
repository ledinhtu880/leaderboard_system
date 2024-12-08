<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Lesson extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'schedule_id',
        'lesson_date',
        'week_index',
        'start_time',
        'end_time'
    ];

    // Quan hệ với Schedule
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    // Quan hệ với Attendance
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Quan hệ với Subject thông qua Schedule
    public function subject()
    {
        return $this->hasOneThrough(
            Subject::class,  // Model cuối cùng
            Schedule::class, // Model trung gian
            'id',            // Khóa chính trong bảng Schedule
            'id',            // Khóa chính trong bảng Subject
            'schedule_id',   // Khóa ngoại trong bảng Lesson trỏ đến Schedule
            'subject_id'     // Khóa ngoại trong bảng Schedule trỏ đến Subject
        );
    }
    public function teacher()
    {
        return $this->hasOneThrough(
            Teacher::class,
            Schedule::class,
            'id',
            'id',
            'schedule_id',
            'teacher_id'
        );
    }
    public function getLessonDateAttribute()
    {
        return Carbon::parse($this->attributes['lesson_date'])->format('d/m/Y');
    }
}
