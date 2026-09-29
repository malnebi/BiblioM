<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Book;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'approved', 'book_id', 'suggested_by'];

    protected function casts(): array
    {
        return ['approved' => 'boolean'];
    }

    public function suggestedBook(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function suggestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'suggested_by');
    }

    public function books(): BelongsToMany{

     return $this->belongsToMany(Book::class);

    }

    

}
