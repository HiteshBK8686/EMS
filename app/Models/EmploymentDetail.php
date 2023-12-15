<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmploymentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'joining_date',
        'notice_period_start_date',
        'resignation_date',
        'resignation_reason',
        'designation',
    ];

    // Relationship
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usere_id');
    }

    public function user_information(): BelongsTo
    {
        return $this->belongsTo(UserInformation::class, 'user_id', 'user_id');
    }
}
