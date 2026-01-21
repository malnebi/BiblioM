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


        $reservedLoans = Loan::where([['user_id','=', $id], ['library_id', '=', Auth::user()->ownLibrary->id], ['description', '=', 'rezervisano']])->get();
        $activeLoans = Loan::where([['user_id','=', $id], ['library_id', '=', Auth::user()->ownLibrary->id], ['active', '=', '1'], ['description', '=', 'potpisano']])->get();
        
        $allLoansCount = Loan::where('user_id','=', $id) ->count();               
        $activeLoansCount = $activeLoans->count(); 
        
        $books = Book::where('library_id', Auth::user()->ownLibrary->id)->where('loan', 0)->get(); // добавља све слободне књиге из библиотеке улогованог корисника    
        
    if (!$user) 
    {
    abort(404);
    }
        $data = [
            'user' => $user,
            'logedUser' => $logedUser,
            'allLoansCount' => $allLoansCount,  
            'activeLoansCount' => $activeLoansCount,
            'reservedLoans' => $reservedLoans,  
            'activeLoans' => $activeLoans,
            'books' => $books,  
            ];

            if (!$logedUser)
                return view('users.logUser', $data);
                else
            return view('users.user', $data);
           

    }


        /**
     * Display the specified resource.
     */
    public function showLogUser($id)
    {
        $user = User::find($id);       
        $loansBooksActive = Loan::where([['user_id','=', $id], ['active', '=', '1'], ['description', '=', 'potpisano' ]])->get();
        $loansBooksReserved = Loan::where([['user_id','=', $id], ['description', '=', 'rezervisano']])->get();
        $loansBooksOver = Loan::where([['user_id','=', $id], ['active', '=', '0'], ['description', '=', null]])->get();
      
        $loans = Loan::where('library_id', Auth::user()->ownLibrary->id)
        ->orderBy('created_at', 'asc')
        ->with(['user', 'book', 'library'])
        ->paginate(20);
        
        $loansNumberOver = $loansBooksOver->count();               
        $loansNumber = $loansBooksActive->count(); 
     
    if (!$user) 
    {
    abort(404);
    }

        $data = [
            'user' => $user,
            'loansNumberOver' => $loansNumberOver,  
            'loansNumber' => $loansNumber,  
            'loansBooksActive' => $loansBooksActive,  
            'loansBooksReserved' => $loansBooksReserved,
            'loansBooksOver' => $loansBooksOver,
            'loans' => $loans,
       
        ];
        //return $id;
        return view('users.logUser', $data);

    
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
