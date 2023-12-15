<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserInformation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_information';

    protected $fillable = [
        'user_id',
        'image',
        'personal_email',
        'emergency_phone',
        'date_of_birth',
        'gender',
        'current_address_id',
        'permanent_address_id',
        'update_data',
        'skills',
    ];

    protected $casts = [
        'update_data' => 'object',
        'skills' => 'array',
    ];

    // Relationship
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function employment_detail(): HasOne
    {
        return $this->hasOne(EmploymentDetail::class, 'user_id', 'user_id');
    }

    public function current_address(): HasOne
    {
        return $this->hasOne(Address::class, 'id', 'current_address_id');
    }

    public function permanent_address(): HasOne
    {
        return $this->hasOne(Address::class, 'id', 'permanent_address_id');
    }
}
