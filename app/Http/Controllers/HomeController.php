<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $books = Book::latest()->with(['library', 'tags'])->get();  // use eager loading

        $tags = Tag::whereNotNull('name') // Избацујемо потпуно празне
            ->where('name', '!=', '')     // Избацујемо празне стрингове
            ->get()
            ->map(function ($tag) {
                $tag->name = trim($tag->name); // Склањамо невидљиве размаке са почетка/краја
                return $tag;
            })
            ->unique('name') // Склањамо дупле тагове ако имају исто име
            ->sortBy('name', SORT_LOCALE_STRING) // Сортирамо азбучно (Ћирилица/Латиница)
            ->values(); // ПРЕСУДНО: Ресетујемо индексе на 0, 1, 2, 3...

        return view('home', [
            'books' => $books,
            'tags' => $tags,
            'limit' => 5
        ]);
    }
}
