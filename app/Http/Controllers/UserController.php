<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Library;
use App\Models\Loan;
use App\Models\Book;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        $library = Library::all();

        $books = Book::latest()->with(['library', 'tags'])->get()->groupBy('featured');  // use eager loading

        return view('users.index', [
            'users' => $user,
            'libraries' => $library,
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);
        $logedUser = User::find(Auth::user()->id);

        $reservedLoans = Loan::where([['user_id', '=', $id], ['library_id', '=', Auth::user()->ownLibrary->id], ['active', '=', null], ['description', '=', 'rezervisano']])->get();
        $inProgressLoans = Loan::where([['user_id', '=', $id], ['active', '=', '1']])
            ->where(function ($query) {
                $query->where('description', '=', null)
                    ->orWhere('description', '=', 'rezervisano');
            })->get();
        $activeLoans = Loan::where([['user_id', '=', $id], ['library_id', '=', Auth::user()->ownLibrary->id], ['active', '=', '1'], ['description', '=', 'potpisano']])->get();

        $returnInProgress = Loan::where('user_id', '=', $id)
            ->where(function ($query) {
                $query->where([['description', '=', null], ['active', '=', '1']]) // potvrđeno vraćanje od strane člana
                    ->orWhere([['description', '=', 'potpisano'], ['active', '=', '0']]); // potvrđeno vraćanje od strane biblioteke
            })->get();

        $overLoans = Loan::where([['user_id', '=', $id], ['active', '=', '0'], ['description', '=', null]])->get();

        $activeLoansCount = $activeLoans->count();
        $overLoansCount = $overLoans->count();
        $allLoansCount = Loan::where('user_id', '=', $id)->count();


        $loans = Loan::where('library_id', Auth::user()->ownLibrary->id)
            ->orderBy('created_at', 'asc')
            ->with(['user', 'book', 'library'])
            ->paginate(20);

        $books = Book::where('library_id', Auth::user()->ownLibrary->id)->where('loan', 0)->get(); // добавља све слободне књиге из библиотеке улогованог корисника    

        if (!$user) {
            abort(404);
        }
        $data = [
            'user' => $user,
            'logedUser' => $logedUser,
            'reservedLoans' => $reservedLoans,
            'inProgressLoans' => $inProgressLoans,
            'returnInProgress' => $returnInProgress,
            'activeLoans' => $activeLoans,
            'activeLoansCount' => $activeLoansCount,
            'overLoans' => $overLoans,
            'overLoansCount' => $overLoansCount,
            'allLoansCount' => $allLoansCount,
            'loans' => $loans,
            'books' => $books,
        ];

        if (!$logedUser)
            return view('users.logUser', $data);
        else
            return view('users.user', $data);
    }

    /**
     * Display the specified resource. Приказ улогованог корисника
     */
    public function showLoggedUser($id)
    {
        $user = User::find($id);
        $reservedLoans = Loan::where([['user_id', '=', $id], ['active', '=', null], ['description', '=', 'rezervisano']])->get();
        $inProgressLoans = Loan::where([['user_id', '=', $id], ['active', '=', '1']])
            ->where(function ($query) {
                $query->where('description', '=', null)->orWhere('description', '=', 'rezervisano');
            })->get();

        $returnInProgress = Loan::where('user_id', '=', $id)
            ->where(function ($query) {
                $query->where([['description', '=', null], ['active', '=', '1']])
                    ->orWhere([['description', '=', 'potpisano'], ['active', '=', '0']]);
            })->get();

        $activeLoans = Loan::where([['user_id', '=', $id], ['active', '=', '1'], ['description', '=', 'potpisano']])->get();
        $overLoans = Loan::where([['user_id', '=', $id], ['active', '=', '0'], ['description', '=', null]])->get();

        $loans = Loan::where('user_id', $id)
            ->orderBy('created_at', 'asc')
            ->with(['user', 'book', 'library'])
            ->paginate(20);

        $overLoansCount = $overLoans->count();
        $loansNumber = $activeLoans->count();

        if (!$user) {
            abort(404);
        }

        $data = [
            'user' => $user,
            'reservedLoans' => $reservedLoans,
            'inProgressLoans' => $inProgressLoans,
            'returnInProgress' => $returnInProgress,
            'activeLoans' => $activeLoans,
            'overLoans' => $overLoans,
            'loansNumber' => $loansNumber,
            'overLoansCount' => $overLoansCount,
            'loans' => $loans,

        ];
        //return $id;
        return view('users.loggedUser', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    /**
     * Display client data.
     */
}
