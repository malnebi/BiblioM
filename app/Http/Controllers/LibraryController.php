<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Loan;
use App\Models\Book;
use App\Models\Library;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    //library members 



    public function index()
    {
        return view('libraries.index');
    }


    public function libMembers()
    {
  
        $members = User::get();  // use eager loading

        $data = [
            'members' => $members,
        ];

        return view('members.index', $data);   
  
    }   

    public function libMember($id)
    {

        $member = User::find($id);       
        //$numberOfAllLoans = Loan::where('member_id','=', $id) ->count();               
//        $numberOfLoans = Loan::where([['member_id','=', $id], ['active', '=', '1']]) ->count(); 
        //$bookName = Loan::where([['member_id','=', $id], ['active', '=', '1']])->get();
         
    if (!$member) 
    {
    abort(404);
    }

        $data = [
            'member' => $member,
//            'numberOfAllLoans' => $numberOfAllLoans,  
  //          'numberOfLoans' => $numberOfLoans,  
    //        'bookName'=> $bookName,
        ];

        return view('members.member', $data);
    }


    /**
     * Display a listing of the resource for specified library.
     */

    public function libBooks($lib)
    {

        $library = Auth::user()->ownLibrary->id;
        $books = Book::where('library_id', $library)->with(['library'])->get();
        $featuredBooks = $books->where('featured', 1);


        $data = [
            'library' => Auth::user()->ownlibrary,
            'featuredBooks' => $featuredBooks,
            'numOfBooks' => $books->count(),
            'books' => $books,
        ];

        return view('homeLib', $data);
    }
     
}
