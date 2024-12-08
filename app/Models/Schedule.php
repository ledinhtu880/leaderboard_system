<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_subject_id',
        'subject_id',
        'teacher_id',
        'room_id',
        'start_hour_name',
        'start_hour_string',
        'end_hour_name',
        'end_hour_string',
        'week_index',
        'from_week',
        'to_week',
        'start_date',
        'end_date'
    ];

    // Quan hệ với Subject
    public function courseSubject()
    {
        return $this->belongsTo(CourseSubject::class, 'course_subject_id', 'id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    // Chuyển đổi start_date và end_date thành Carbon
    public $timestamps = false;
    protected $dates = [
        'start_date',
        'end_date'
    ];
    public function members()
    {
        return $this->belongsToMany(
            Member::class,
            'member_schedules',
            'schedule_id',
            'member_id'
        )->withPivot('registered_at');
    }
}
