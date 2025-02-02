<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $books = Book::latest()->with(['library', 'tags'])->get()->groupBy('featured');  // use eager loading

        return view('home' , [
            'featuredBooks' => $books[1],
            'books' => $books[0],
            'tags' => Tag::all(), 
        ]);

        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        try{

            $attributes = $request->validate([
                'author_fname' => ['required', 'string', 'max:50'],
                'author_lname' => ['required', 'string', 'max:50'],
                'title' => ['required', 'string', 'max:200'],
                'publisher_name' => ['required', 'string', 'max:100'],
                'publisher_place' => ['required', 'string', 'max:100'],
                'year' => ['required', 'string', 'max:4'],
                'tags' => ['nullable'],
            ]);
            
            $attributes['featured'] = $request->has('featured');
            
            dd($attributes);

            $book = Auth::user()->library->books()->create(Arr::except($attributes, 'tags'));
            
            dd($book);

            if($attributes['tags']) {
                foreach  (explode(',', $attributes['tags']) as $tag) {  // laravel,beckend, frontend will be turned into array [ 'laravel','beckend','frontend' ]
                    $book->tag($tag);       
                    
                }
            }
            return dd($attributes);
//redirect('/');
        } catch  (\Exception $e) {
            // Handle the exception, e.g., log the error, display an error message to the user
            Log::error($e->getMessage());
            return back()->with('error', 'Failed to save the book');
        }
            
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        //
    }

}
