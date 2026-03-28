<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Library;

class SearchController extends Controller
{

    public function __invoke()
    {

        $searchTerm = request('q');
        $books = collect(); // Празно по дефолту

        if (strlen($searchTerm) >= 3) {
            // Овде иде онај код изнад...
            $searchTerm = request('q');

            $books = Book::query()
                ->with('tags') // Eager loading да не би имала N+1 проблем
                ->where(function ($query) use ($searchTerm) {
                    // Претрага по основним пољима књиге
                    $query->where('title', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('author_fname', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('author_lname', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('publisher_name', 'LIKE', "%{$searchTerm}%");

                    // ГЛАВНИ ДЕО: Претрага кроз релацију са ознакама
                    $query->orWhereHas('tags', function ($tagQuery) use ($searchTerm) {
                        $tagQuery->where('name', 'LIKE', "%{$searchTerm}%");
                    });
                })
                ->get();
        }

        $data = [
            'books' => $books,
        ];

        return view('results', $data);
    }

    public function oneLibraryBooks($id)
    {

        $library = Library::findOrFail($id);
        $searchTerm = request('q');

        $books = $library->books() // Ово аутоматски додаје 'where library_id = X'
            ->with('tags')
            ->where(function ($query) use ($searchTerm) {
                $query->where('title', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('author_fname', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('author_lname', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('publisher_name', 'LIKE', "%{$searchTerm}%")
                    ->orWhereHas('tags', function ($tagQuery) use ($searchTerm) {
                        $tagQuery->where('name', 'LIKE', "%{$searchTerm}%");
                    });
            })
            ->get();


        $data = [
            'books' => $books,
        ];

        return view('results', $data);
    }
}
