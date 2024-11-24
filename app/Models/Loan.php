<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Book;
use App\Models\Library;
use App\Models\User;


class Loan extends Model
{
    use HasFactory;

    public function book()
    {
        return $this->belongsTo(Book::class);
    }   

    public function user() // koji je pozajmio knjigu
    {
        return $this->belongsTo(User::class);
    }   

           
  public function library() // koja je vlasnik knjige   
    {
        return $this->belongsTo(Library::class);
    }    
}
