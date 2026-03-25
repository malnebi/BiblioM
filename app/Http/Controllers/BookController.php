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

        return view('books.create', ['tags' => Tag::all()]
);
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
{
    // 1. Валидација (пази на имена поља из наше компоненте)
    $attributes = $request->validate([
        'author_fname' => ['required', 'string', 'max:50'],
        'author_lname' => ['required', 'string', 'max:50'],
        'title' => ['required', 'string', 'max:200'],
        'publisher_name' => ['required', 'string', 'max:100'],
        'publisher_place' => ['required', 'string', 'max:100'],
        'year' => ['required', 'integer', 'min:1000', 'max:' . (date('Y') + 1)],
      //  'book_image' => ['nullable', 'image', 'max:2048'],
        // Овде валидирамо ID-еве за падајуће меније
        'tag1' => ['nullable', 'exists:tags,id'],
        'tag2' => ['nullable', 'exists:tags,id'],
        'tag3' => ['nullable', 'exists:tags,id'],
        // Овде валидирамо текст за нову ознаку
        'suggested_tag' => ['nullable', 'string', 'max:30'],
    ]);

    $library = Auth::user()->ownLibrary;
    $lastLibBookId = $library->books()->max('lib_book_id') ?? 0;

    // Снимање слике
    $imagePath = $request->hasFile('book_image') 
        ? $request->file('book_image')->store('book_covers', 'public') 
        : null;

    // 2. Креирање књиге
    $book = $library->books()->create([
        'lib_book_id' => $lastLibBookId + 1,
        'author_fname' => $attributes['author_fname'],
        'author_lname' => $attributes['author_lname'],
        'title' => $attributes['title'],
        'publisher_name' => $attributes['publisher_name'],
        'publisher_place' => $attributes['publisher_place'],
        'year' => $attributes['year'],
      //  'book_image' => $imagePath,
        'loan' => 0,
    ]);

    // 3. ПОВЕЗИВАЊЕ ОЗНАКА (Pivot логика)

    // А) Повезивање изабраних ознака преко ID-а (attach)
    $selectedTags = collect([$request->tag1, $request->tag2, $request->tag3])->filter();
    
    if ($selectedTags->isNotEmpty()) {
        $book->tags()->attach($selectedTags);
    }

    // Б) Креирање и повезивање нове ознаке преко твоје методе tag()
    if ($request->filled('suggested_tag')) {
        $book->tag($request->suggested_tag);
    }

    return redirect('/books')->with('success', 'Књига је успешно додата у библиотеку!');
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

        $isReservedByMe = $book->isReservedBy(Auth::id());
        $isBorrowedByMe = $book->isBorrowedByMe(Auth::id());
        $data = [

            'book' => $book,
            'isReservedByMe' => $isReservedByMe,
            'isBorrowedByMe' => $isBorrowedByMe,
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

        $request->validate([
            /**Validacija podataka */
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

        $loans = Loan::where([
            ['user_id', '=', Auth::user()->id],
            ['book_id', '=', $book->id],
            ['library_id', '=', $book->library_id],
            ['description', '=', 'rezervisano']
        ])->first();


        if ($loans) {

            return redirect()->action([BookController::class, 'show'], [$book->id])->with('error', 'Knjiga je već rezervisana ili pozajmljena.');
        }
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
