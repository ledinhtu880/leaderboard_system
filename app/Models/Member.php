<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Member extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'gpa',
        'last_gpa',
        'final_score',
        'personality',
        'hobby',
    ];

    protected $casts = [
        'gpa' => 'float',
        'last_gpa' => 'float',
        'final_score' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_member')
            ->withPivot(['compatibility_score', 'status'])
            ->withTimestamps();
    }

    public function suggestedGroups(): BelongsToMany
    {
        return $this->groups()
            ->wherePivot('status', 'suggested')
            ->orderByPivot('compatibility_score', 'desc');
    }

    public function confirmedGroups(): BelongsToMany
    {
        return $this->groups()
            ->wherePivot('status', 'confirmed');
    }
}
