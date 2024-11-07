<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\Library;
use App\Models\Tag;

class Book extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'author_fname', // Add this line
        'author_lname', // Add this line
        'title',
        'year', 
    ];

    public function tag(string $name): void{

        $tag = Tag::firstOrCreate(['name' => $name]);

     $this->tags()->attach($tag);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function library(): BelongsTo
    {
        return $this->belongsTo(Library::class);
    }
    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }
    public function loans()
    {
        return $this->hasMany('App\Models\Loan');
    }


}
