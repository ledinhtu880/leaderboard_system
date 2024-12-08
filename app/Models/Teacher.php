<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'display_name'
    ];

    // Không sử dụng timestamps
    public $timestamps = false;

    // Quan hệ với Schedule
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'teacher_id', 'id');
    }
}
