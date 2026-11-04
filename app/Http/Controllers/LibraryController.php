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


    public function show(Request $request, $id) // prikaži biblioteku člana sa svim knjigama njegove biblioteke
    {
        $library = Library::findOrFail($id);
        $allBooksCount = $library->books()->count();
        $books = $library->books()
            ->with(['library', 'tags', 'loans'])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(8)
            ->withQueryString();
        $bookSearch = trim((string) $request->query('q', ''));
        $panel = $request->query('panel');
        $panel = in_array($panel, ['search-books', 'all-books'], true) ? $panel : null;

        if ($panel === 'all-books') {
            $bookSearch = '';
        }

        $activePanel = $panel ?? ($bookSearch !== '' ? 'search-books' : null);
        $searchBooks = collect();

        if ($activePanel === 'search-books' && mb_strlen($bookSearch) >= 3) {
            $searchBooks = $library->books()
                ->with(['library', 'tags', 'loans'])
                ->where(function ($query) use ($bookSearch) {
                    $query->where('title', 'LIKE', "%{$bookSearch}%")
                        ->orWhere('author_fname', 'LIKE', "%{$bookSearch}%")
                        ->orWhere('author_lname', 'LIKE', "%{$bookSearch}%")
                        ->orWhere('publisher_name', 'LIKE', "%{$bookSearch}%")
                        ->orWhereHas('tags', function ($tagQuery) use ($bookSearch) {
                            $tagQuery->where('name', 'LIKE', "%{$bookSearch}%");
                        });
                })
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->get();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'html' => view('books.partials.cards', compact('books'))->render(),
                'next_page_url' => $books->nextPageUrl(),
            ]);
        }

        $data = [
            'library' => $library,
            'books' => $books,
            'isOwnLibrary' => Auth::id() === (int) $library->owner_id,
            'allBooksCount' => $allBooksCount,
            'searchBooks' => $searchBooks,
            'bookSearch' => $bookSearch,
            'bookSearchTooShort' => $activePanel === 'search-books' && $bookSearch !== '' && mb_strlen($bookSearch) < 3,
            'activePanel' => $activePanel,
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


    /** 
     * prikaz svih knjiga biblioteke ulogovanog člana
     *  */
    public function libraryBooks(Request $request, $id)
    {

        $library = Library::findOrFail($id);
        $user = User::find($library->owner_id);
        $allBooksCount = $library->books()->count();
        $bookSearch = trim((string) $request->query('q', ''));
        $panel = $request->query('panel');
        $panel = in_array($panel, ['search-books', 'all-books'], true) ? $panel : null;

        if ($panel === 'all-books') {
            $bookSearch = '';
        }

        $activePanel = $panel ?? ($bookSearch !== '' ? 'search-books' : null);
        $booksQuery = $library->books()
            ->with(['library', 'tags', 'loans'])
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        if ($activePanel === 'search-books') {
            if ($bookSearch === '' || mb_strlen($bookSearch) < 3) {
                $booksQuery->whereRaw('1 = 0');
            } else {
                $booksQuery->where(function ($query) use ($bookSearch) {
                    $query->where('title', 'LIKE', "%{$bookSearch}%")
                        ->orWhere('author_fname', 'LIKE', "%{$bookSearch}%")
                        ->orWhere('author_lname', 'LIKE', "%{$bookSearch}%")
                        ->orWhere('publisher_name', 'LIKE', "%{$bookSearch}%")
                        ->orWhereHas('tags', function ($tagQuery) use ($bookSearch) {
                            $tagQuery->where('name', 'LIKE', "%{$bookSearch}%");
                        });
                });
            }
        }

        $books = $booksQuery->paginate(8)->withQueryString();

        if ($request->expectsJson()) {
            return response()->json([
                'html' => view('books.partials.cards', compact('books'))->render(),
                'next_page_url' => $books->nextPageUrl(),
            ]);
        }

        $data = [
            'library' => $library,
            'user' => $user,
            'books' => $books,
            'isOwnLibrary' => Auth::id() === (int) $library->owner_id,
            'allBooksCount' => $allBooksCount,
            'bookSearch' => $bookSearch,
            'bookSearchTooShort' => $activePanel === 'search-books' && $bookSearch !== '' && mb_strlen($bookSearch) < 3,
            'activePanel' => $activePanel,
        ];

        return view('libraries.libraryBooks', $data);
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
