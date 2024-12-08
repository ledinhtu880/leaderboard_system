<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSubject extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject_id',
        'display_name',
        'course_year_code',
        'status'
    ];

    public $timestamps = false;

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'course_subject_id', 'id');
    }
}
