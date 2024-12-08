<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberSchedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'member_id',
        'schedule_id',
        'registered_at'
    ];

    public $timestamps = false;

    // Quan hệ với Member
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

    // Quan hệ với Schedule
    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'id');
    }
}
