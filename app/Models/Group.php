<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'group_member')
            ->withPivot(['compatibility_score', 'status'])
            ->withTimestamps();
    }

    public function suggestedMembers(): BelongsToMany
    {
        return $this->members()
            ->wherePivot('status', 'suggested')
            ->orderByPivot('compatibility_score', 'desc');
    }

    public function confirmedMembers(): BelongsToMany
    {
        return $this->members()
            ->wherePivot('status', 'confirmed');
    }

    public function getAverageGpaAttribute()
    {
        return $this->members()->avg('gpa');
    }

    public function getAverageCompatibilityScoreAttribute()
    {
        return $this->members()
            ->wherePivot('status', 'suggested')
            ->avg('group_member.compatibility_score');
    }
}
