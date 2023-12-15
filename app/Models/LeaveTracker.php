<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveTracker extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'authorizer_user_id',
        'date',
        'note',
        'day',
    ];

    // Relationship
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function authorizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'authorizer_user_id');
    }
}
