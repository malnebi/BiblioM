<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Book;
use App\Models\Client;
use App\Models\User;


class Loan extends Model
{
    use HasFactory;

    public function book()
    {
        return $this->belongsTo(Book::class);
    }   

    public function client()
    {
        return $this->belongsTo(Client::class);
    }   

           
  public function user()    
    {
        return $this->belongsTo(User::class);
    }    
}
