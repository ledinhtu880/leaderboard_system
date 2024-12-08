<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->through('schedule')->has('subject');
    }

    // Quan hệ với Room thông qua Schedule
    public function room()
    {
        return $this->through('schedule')->has('room');
    }

    // Quan hệ với Teacher thông qua Schedule
    public function teacher()
    {
        return $this->through('schedule')->has('teacher');
    }
}
