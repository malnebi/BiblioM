<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Tag;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()   //  stranica sa svim knjigama u navigaciji je pod Zajednica
    {
        $books = Book::latest()->with(['library', 'tags'])->get()->groupBy('featured');  // use eager loading

        return view('books.index', [
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

        $libraryId = Auth::user()->ownLibrary->id;

        $lastLibBookId = Book::where('library_id', $libraryId)->max('lib_book_id'); // 
        $libBookId = $lastLibBookId ? $lastLibBookId + 1 : 1; //

        $attributes = $request->validate([
            /**Validacija podataka */
            'author_fname' => ['required', 'string', 'max:50'],
            'author_lname' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:200'],
            'publisher_name' => ['required', 'string', 'max:100'],
            'publisher_place' => ['required', 'string', 'max:100'],
            'year' => ['required', 'string', 'max:4'],
            'tags' => ['nullable'],
        ]);

        $book = new Book;
        $book->library_id = Auth::user()->ownLibrary->id;
        $book->lib_book_id = $libBookId; // id knjige jedne biblioteke u bazi 
        $book->author_fname = $request->author_fname;
        $book->author_lname = $request->author_lname;
        $book->title = $request->title;
        $book->publisher_name = $request->publisher_name;
        $book->publisher_place = $request->publisher_place;
        $book->year = $request->year;
        $book->loan = 0;

        $attributes['featured'] = $request->has('featured');
        $attributes['lib_book_id'] = $libBookId;  

        $book = Auth::user()->ownLibrary->books()->create(Arr::except($attributes, 'tags'));

        if ($attributes['tags']) {
            foreach (explode(',', $attributes['tags']) as $tag) {  // tag1, tag2, tag3  will be turned into array [ 'tag1','tag2','tag3']
                $book->tag($tag);
            }

            $book->save();

//            return dd($libraryId, $lastLibBookId, $libBookId, $attributes, $book);
            return redirect('books');
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //     $numberOfLoans = Loan::where('loans.book_id','=', $id )->count(); 
        //     $clientName=Loan::where([['book_id','=', $id]])->get(); // ime klienta koji je poyajmio knjigu

        if (!$book) {
            abort(404);
        }

        $data = [

            'book' => $book,
            //            'numberOfLoans' => $numberOfLoans,
            //            'clientName' => $clientName,  
        ];
        return view('books.book', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        
      return view('books.edit', ['book' => $book,]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
                
        $request->validate([         /**Validacija podataka */
            'year' => ['required', 'string', 'max:4'],
            'title' => ['required', 'string', 'max:200'],
        ]);

        $book->author_fname = $request->author_fname;
        $book->author_lname = $request->author_lname;
        $book->title = $request->title;
        $book->publisher_name = $request->publisher_name;
        $book->publisher_place = $request->publisher_place;
        $book->year = $request->year;
        
        $book->save();

        return redirect('books');

    }

     public function bookReservation(Request $request, Book $book)
  {    

      $loan = new Loan;
        
        $loan->user_id = Auth::user()->id;
        $loan->book_id = $book->id; 
        $loan->library_id = $book->library_id;  
        $loan->description = 'rezervisano';  
        $loan->active = $request->active;
        $loan->save();

        
        return redirect()->action([UserController::class, 'showLoggedUser'], [$loan->user_id]);
    }
 

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return redirect('books');
 
    }

}
