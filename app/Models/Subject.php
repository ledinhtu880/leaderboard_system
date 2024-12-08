<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'id',
        'subject_code',
        'subject_name',
    ];

    // Không sử dụng timestamps
    public $timestamps = false;

    // Quan hệ với Schedule
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'subject_id', 'id');
    }
}
