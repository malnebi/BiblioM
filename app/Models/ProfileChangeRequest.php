<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileChangeRequest extends Model
{
    protected $fillable = [
        'user_id',
        'requested_name',
        'requested_last_name',
        'requested_photo',
        'requested_role_type',
        'requested_role_details',
        'library_id',
        'requested_library_name',
        'requested_library_logo',
        'status',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return ['reviewed_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function library(): BelongsTo
    {
        return $this->belongsTo(Library::class);
    }
}