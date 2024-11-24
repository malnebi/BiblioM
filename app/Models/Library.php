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


    /*    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
    */

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function members()
    {
        return $this->belongsToMany(User::class, 'library_user', 'library_id', 'user_id');
    }
}
