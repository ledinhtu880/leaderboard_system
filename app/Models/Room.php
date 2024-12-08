<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name'
    ];

    // Không sử dụng timestamps
    public $timestamps = false;

    // Quan hệ với Schedule
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'room_id', 'id');
    }
}
