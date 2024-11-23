<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\Pivot;

class GroupMember extends Pivot
{
    protected $table = 'group_member';

    protected $fillable = [
        'group_id',
        'member_id',
        'compatibility_score',
        'status',
    ];

    protected $casts = [
        'compatibility_score' => 'float',
        'status' => 'string',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
