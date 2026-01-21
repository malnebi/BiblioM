<?php

namespace App\Http\Controllers;

use App\Models\Library;
use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use Carbon\Carbon;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() //
    {
        $loans = Loan::where('library_id', Auth::user()->ownLibrary->id)
            ->orderBy('created_at', 'asc')
            ->with(['user', 'book', 'library'])
            ->paginate(20);


        $data = [
            'loans' => $loans,
        ];
        return view('loans.index', $data);
        //  return dd ($loans->toArray());

    }

    /**
     * Show the form for creating a new resource.
     */


    public function create($userId)
    { {
            $user = User::find($userId);
            $book = Book::where('library_id', Auth::user()->ownLibrary->id)->where('loan', 0)->get(); // добавља све слободне књиге из библиотеке улогованог корисника 

            $data = [
                'users' => $user,
                'books' => $book,
            ];

            return view('loans.create', $data);
        }
    }
    /**
     * 
     * ПОЗАЈМИЦА
     * 
     * Унос записа о позајмици.
     */
    public function store(Request $request)
    {
        //return $request->date;        //$time = Carbon::parse($request->time);


        $loan = new Loan;

        $loan->user_id = $request->user_id;
        $loan->book_id = $request->book_id;
        $loan->library_id = Auth::user()->ownLibrary->id;
        $loan->return_deadline = Carbon::now()->addDays(30);   // rok za vraćanje knjige je 30 dana //$loan->return_deadline = $time->format("Y-m-d");
        $loan->description = $request->description;
        $loan->active = 1;
        $loan->save();

        /** upis u tabelu book */

        $book = Book::find($loan->book_id);        // traži knjigu u tabeli book na osnovu broja klijent u loan
        $book->loan = 1;                              // zaduženje knjige je aktivno 
        $book->lib_user_id = $loan->user_id;           // upis broja korisnika koji je zadužio knjigu 
        $book->save();

        //        $id_user = $loan->user_id;

        return redirect()->action([UserController::class, 'show'], [$loan->user_id]);
    }

    /**
     * Библиотека потврђује позајмицу.
     */
    public function loanLibraryConfirm($loan)
    {
        $loan = Loan::find($loan);
        $loan->active = 1;
        $loan->return_deadline = Carbon::now()->addDays(30);   // rok za vraćanje knjige je 30 dana
        $loan->save();

        /** upis u tabelu book */
        if ($loan->active == 1) {
            $book = Book::find($loan->book_id);        // traži knjigu u tabeli book na osnovu broja klijent u loan
            $book->loan = 1;                              // zaduženje knjige je aktivno 
            $book->save();
        }
        return redirect()->action([UserController::class, 'show'], [$loan->user_id]);
    }

    /**
     * Члан потврђује позајмицу.
     */
    public function loanMemberConfirm($loan)
    {

        $loan = Loan::find($loan);
        $loan->description = 'potpisano';
        $loan->save();


        /** upis u tabelu book */
        if ($loan->loan == 1) {
            $book = Book::find($loan->book_id);        // traži knjigu u tabeli book na osnovu broja klijent u loan
            $book->loan = 1;                              // zaduženje knjige je aktivno 
            $book->save();
        }
        return redirect()->action([UserController::class, 'showLogUser'], [$loan->user_id]);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        //
    }

    /**
     * Update the specified resource in storage.  VRAĆANJE KNJIGE!
     */
    public function update($id)
    { {

            $loan = Loan::find($id);
            $loan->active = 0;
            $loan->save();

            $book = Book::find($loan->book_id);        // traži knjigu u tabeli book na osnovu broja knjige u tabeli zaduzanja
            if ($loan->description == null) {
                $book->loan = 0;                            // zaduženje je neaktivno  - knjiga je slobodna)
            }
            $book->save();

            return redirect()->action([UserController::class, 'show'], [$loan->user_id]);
        }
    }

    /**
     * Update the specified resource in storage.    PRODUZAVA ROK ZA VRAĆANJE KNJIGE!!!
     */

    public function updateReturnDate($id)  // mijenja datum za 30 dana od danas
    {

        $loan = Loan::find($id);
        $loan->return_deadline = Carbon::now()->addDays(60);   // rok za vraćanje knjige je 60 dana    
        $loan->save();


        return redirect()->action([UserController::class, 'show'], [$loan->user_id]);
    }
}
