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


    public function myLibraryLoans($id) //
    {

        $library = Library::find($id);
        $reservedLoans = Loan::where([['library_id', '=', $id], ['active', '=', null], ['description', '=', 'rezervisano']])->get();

        $inProgressLoans = Loan::where([['library_id', '=', $id], ['active', '=', '1']])
            ->where(function ($query) {
                $query->where('description', '=', null)->orWhere('description', '=', 'rezervisano');
            })->get();

        $returnInProgress = Loan::where('library_id', '=', $id)
            ->where(function ($query) {
                $query->where([['description', '=', null], ['active', '=', '1']])
                    ->orWhere([['description', '=', 'potpisano'], ['active', '=', '0']]);
            })->get();

        $activeLoans = Loan::where([['library_id', '=', $id], ['active', '=', '1'], ['description', '=', 'potpisano']])->get();
        $overLoans = Loan::where([['library_id', '=', $id], ['active', '=', '0'], ['description', '=', null]])->get();

        $loans = Loan::where('library_id', Auth::user()->ownLibrary->id)
            ->orderBy('created_at', 'asc')
            ->with(['user', 'book', 'library'])
            ->paginate(20);

        $overLoansCount = $overLoans->count();
        $loansNumber = $activeLoans->count();

        $groupedOverLoans = Loan::with(['book', 'user'])
            ->where('library_id', $id)
            ->where('description', null)
            ->where('active', '0')
            ->get()
            ->groupBy('book_id');

        $groupedActiveLoans = $activeLoans->groupBy('book_id');
        $groupedReservedLoans = $reservedLoans->groupBy('book_id');
        $groupedProgressLoans = $inProgressLoans->groupBy('book_id');

        $data = [
            'library' => $library,
            'reservedLoans' => $reservedLoans,
            'inProgressLoans' => $inProgressLoans,
            'returnInProgress' => $returnInProgress,
            'activeLoans' => $activeLoans,
            'overLoans' => $overLoans,
            'loansNumber' => $loansNumber,
            'overLoansCount' => $overLoansCount,
            'loans' => $loans,
            'groupedOverLoans' => $groupedOverLoans,
            'groupedActiveLoans' => $groupedActiveLoans,
            'groupedReservedLoans' => $groupedReservedLoans,
            'groupedProgressLoans' => $groupedProgressLoans,
        ];
        return view('loans.myLibraryLoans', $data);
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
    public function loanBookMemberConfirm($loan)
    {

        $loan = Loan::find($loan);
        $loan->description = 'potpisano';
        $loan->save();


        /** upis u tabelu book */
        if ($loan->loan == 1) {
            $book = Book::find($loan->book_id);        // traži knjigu u tabeli book na osnovu broja klijent u loan
            $book->loan = 1;  
            $book->lib_user_id = $loan->user_id;                            // zaduženje knjige je aktivno 
            $book->save();
        }
        return redirect()->action([UserController::class, 'showLoggedUser'], [$loan->user_id]);
    }

    /**
     * Библиотека потврђује враћање књиге.
     */

    public function returnBookLibraryConfirm($loan)
    {
        $loan = Loan::find($loan);
        $loan->active = 0;
        $loan->description = null;
        $loan->updated_at = Carbon::now();   // datum vraćanja knjige 
        $loan->save();

        /** upis u tabelu book */
        $book = Book::find($loan->book_id);        // traži knjigu u tabeli book na osnovu broja klijent u loan
        $book->loan = 0;                    // zaduženje je neaktivno  - knjiga je slobodna)
        $book->lib_user_id = null;                              
        $book->save();

        return redirect()->action([UserController::class, 'show'], [$loan->user_id]);
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
