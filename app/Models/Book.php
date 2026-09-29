<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\Library;
use App\Models\Loan;
use App\Models\Tag;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'library_id',
        'lib_book_id',
        'author_fname', // Add this line
        'author_lname', // Add this line
        'title',
        'publisher_name',
        'publisher_place',
        'year',
        'book_cover',
    ];

 
    public function tag(string $name): void
    {
        $tag = Tag::where('approved', true)->where('name', $name)->first();
        if ($tag) {
            $this->tags()->syncWithoutDetaching([$tag->id]);
        }
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function library(): BelongsTo
    {
        return $this->belongsTo(Library::class);
    }
    public function user()  // user of the library that borrowes librarys book
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function userBorrows()
    {
        return $this->belongsTo(User::class, 'lib_user_id');
    }

    public function isReservedBy(int $userId): bool
    {
        return $this->loans()
            ->where('description', 'rezervisano')
            ->where('user_id', $userId)
            ->exists();
    }

    public function isBorrowedByMe(int $userId): bool
    {
        return $this->loans()
            ->where('active', '1')
            ->where('description', 'potpisano')
            ->where('user_id', $userId)
            ->exists();
    }
}
