<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Tag;

class SearchController extends Controller
{
    
    public function __invoke()
    {
        $books = Book::query()
        ->with(['library', 'tags'])
        ->where('title', 'LIKE', '%'.request('q').'%')
        ->get();

        // return $jobs; // view search results in JSON 

        return view('results', ['books' => $books] );  // pass throught list of jobs

    }


    
}
