<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Library;
use App\Models\Loan;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $user = User::all();  

        return view('users.index', [
            'users' => $user,
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
      //  $numberOfAllLoans = Loan::where('user_id','=', $id) ->count();               
        //$numberOfLoans = Loan::where([['user_id','=', $id], ['active', '=', '1']]) ->count(); 
        //$bookName = Loan::where([['user_id','=', $id], ['active', '=', '1']])->get();
         
    if (!$user) 
    {
    abort(404);
    }

        $data = [
            'user' => $user,
            //'numberOfAllLoans' => $numberOfAllLoans,  
            //'numberOfLoans' => $numberOfLoans,  
            //'bookName'=> $bookName,
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
