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

        $library = Library::all();

        $data = [
            'libraries' => $library,
        ];

        return view('libraries.index', $data);
    }


    public function show($id) // prikaži biblioteku člana sa svim knjigama njegove biblioteke i zaduženjima biblioteke ulogovanog člana 
    {
        $library = Library::find($id);
        $user = User::find($library->owner_id);      
        $books = Book::where('library_id', $id)->with(['library'])->get();
        $featuredBooks = $books->where('featured', 1);
        $loans = Loan::where('library_id', Auth::user()->ownLibrary->id)
                ->orderBy('created_at', 'asc')
                ->with(['user', 'book', 'library'])
                ->paginate(20);                             
                
                $data = [
                    'library' => $library,
                    'user' => $user,
                    'books' => $books,
                    'featuredBooks' => $featuredBooks,
                    'loans' => $loans,
        ];

        return view('libraries.library', $data);
    }
    
    
    
    /**
     * Display a listing of the resource for specified library.
     */

    public function libBooks($lib)
    {

        $library = Auth::user()->ownLibrary->id;
        $books = Book::where('library_id', $library)->with(['library'])->get();
        $featuredBooks = $books->where('featured', 1);
                $loans = Loan::where('library_id', Auth::user()->ownLibrary->id)
                ->orderBy('created_at', 'asc')
                ->with(['user', 'book', 'library'])
                ->paginate(20);
            
            
            $data = [
                'library' => Auth::user()->ownlibrary,
                'featuredBooks' => $featuredBooks,
                'numOfBooks' => $books->count(),
                'books' => $books,
                'loans' => $loans,
        ];

        return view('homeLib', $data);
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

        if (!$member) {
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


}
