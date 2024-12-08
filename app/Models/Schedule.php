<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject_id',
        'teacher_id',
        'room_id',
        'start_week',
        'end_week',
        'week_index',
    ];

    // Quan hệ với Subject

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
}
