<?php

namespace App\Models;

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
        'phone',
        'gpa',
        'birthdate',
        'email',
        'class',
    ];

    protected $casts = [
        'gpa' => 'float',
        'last_gpa' => 'float',
        'subject_1_mark' => 'float',
        'subject_2_mark' => 'float',
        'subject_3_mark' => 'float',
    ];

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
    public function getBirthdateAttribute(): string
    {
        return date('d/m/Y', strtotime($this->attributes['birthdate']));
    }
}
