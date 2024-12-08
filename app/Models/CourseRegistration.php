<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseRegistration extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'course_registrations';
    protected $fillable = [
        'member_id',
        'subject_id',
        'registration_date',
        'status'
    ];
    // Quan hệ với Member
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
    // Quan hệ với Subject
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    // Scope để lọc các đăng ký còn hiệu lực
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
