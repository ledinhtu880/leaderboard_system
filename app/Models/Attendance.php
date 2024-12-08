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
        'status',
        'volunteered',
        'volunteer_points',
    ];

    protected $casts = [
        'volunteered' => 'boolean'
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function attendanceSession()
    {
        return $this->belongsTo(AttendanceSession::class);
    }
}
