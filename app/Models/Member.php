<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Member extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'name',
        'gpa',
        'last_gpa',
    ];

    protected $casts = [
        'gpa' => 'float',
        'last_gpa' => 'float',
    ];

    public function groupMemberships()
    {
        return $this->hasMany(GroupMember::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function confirmedGroups(): BelongsToMany
    {
        return $this->groups()
            ->wherePivot('status', 'confirmed');
    }
    public function getGetFirstCharacterAttribute(): ?string
    {
        $result = DB::table('members')
            ->selectRaw("RIGHT(RTRIM(SUBSTRING(REVERSE(name), 1, LOCATE(' ', REVERSE(name)) - 1)), 1) AS first_character")
            ->where('id', $this->id)
            ->first();

        return $result ? $result->first_character : null;
    }
}
