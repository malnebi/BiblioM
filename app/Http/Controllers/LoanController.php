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


    public function myLibraryLoans($id)
    {

        if (Auth::user()->ownLibrary->id == $id) {
            $library = Library::find($id);

            // Помоћна функција да не бисмо понављали исти код
            $baseQuery = Loan::where('library_id', $id)->whereHas('book')->whereHas('user');

            // 1. Резервисано
            $reservedLoans = (clone $baseQuery)->where([['active', '=', null], ['description', '=', 'rezervisano']])->get();

            // 2. У току (издавање)
            $inProgressLoans = (clone $baseQuery)->where('active', '1')
                ->where(function ($query) {
                    $query->where('description', '=', null)->orWhere('description', '=', 'rezervisano');
                })->get();

            // 3. У току (враћање)
            $returnInProgress = (clone $baseQuery)->where(function ($query) {
                $query->where([['description', '=', null], ['active', '=', '1']])
                    ->orWhere([['description', '=', 'potpisano'], ['active', '=', '0']]);
            })->get();

            // 4. Активна задужења
            $activeLoans = (clone $baseQuery)->where([['active', '=', '1'], ['description', '=', 'potpisano']])->get();

            // 5. Завршена задужења
            $overLoans = (clone $baseQuery)->where([['active', '=', '0'], ['description', '=', null]])->get();

            // 6. Главна листа са пагинацијом
            $loans = (clone $baseQuery)->orderBy('created_at', 'asc')
                ->with(['user', 'book', 'library'])
                ->paginate(20);

            $loansNumber = $activeLoans->count();
            $overLoansCount = $overLoans->count();

            // Груписање (сада је сигурно јер baseQuery филтрира дух-књиге)
            $groupedOverLoans = $overLoans->groupBy('book_id');
            $groupedActiveLoans = $activeLoans->groupBy('book_id');
            $groupedReservedLoans = $reservedLoans->groupBy('book_id');
            $groupedProgressLoans = $inProgressLoans->groupBy('book_id');

            return view('loans.myLibraryLoans', compact(
                'library',
                'reservedLoans',
                'inProgressLoans',
                'returnInProgress',
                'activeLoans',
                'overLoans',
                'loansNumber',
                'overLoansCount',
                'loans',
                'groupedOverLoans',
                'groupedActiveLoans',
                'groupedReservedLoans',
                'groupedProgressLoans'
            ));
        } else {
            abort(404);
        }
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

        // return redirect()->action([UserController::class, 'show'], [$loan->user_id]);
        return redirect()->action([LoanController::class, 'myLibraryLoans'], [$loan->library_id]);
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
