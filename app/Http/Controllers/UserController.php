<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Library;
use App\Models\Loan;
use App\Models\Book;
use App\Models\Tag;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $user = User::all();  
         $library = Library::all();

        return view('users.index', [
            'users' => $user,
            'libraries' => $library,
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
        $allLoansNumber = Loan::where('user_id','=', $id) ->count();               
        $loansNumber = Loan::where([['user_id','=', $id], ['active', '=', '1']]) ->count(); 
        $loansBooks = Loan::where([['user_id','=', $id], ['active', '=', '1']])->get();
     
    if (!$user) 
    {
    abort(404);
    }

        $data = [
            'user' => $user,
            'allLoansNumber' => $allLoansNumber,  
            'loansNumber' => $loansNumber,  
            'loansBooks' => $loansBooks,  
        ];
        //return $id;
        return view('users.user', $data);
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
