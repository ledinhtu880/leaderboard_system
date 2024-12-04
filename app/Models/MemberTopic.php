<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberTopic extends Model
{
    use HasFactory;
    protected $fillable = ['member_id', 'topic_id', 'status'];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
