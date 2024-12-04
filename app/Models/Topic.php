<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name',
        'description',
    ];
    public function members()
    {
        return $this->hasMany(MemberTopic::class, 'topic_id');
    }

    public function pendingMembers()
    {
        return $this->members()->where('status', 'pending');
    }

    public function lockedMembers()
    {
        return $this->members()->where('status', 'locked');
    }
}
