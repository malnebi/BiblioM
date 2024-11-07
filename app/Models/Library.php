<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Book;

class Library extends Model
{
    use HasFactory;


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
