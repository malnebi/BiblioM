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
    public function index(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'tag_q' => ['nullable', 'string', 'max:100'],
            'panel' => ['nullable', Rule::in(['latest-books', 'all-tags'])],
        ]);
        $bookSearch = trim($validated['q'] ?? '');
        $tagSearch = trim($validated['tag_q'] ?? '');
        $panel = $validated['panel'] ?? null;

        if ($panel !== null) {
            $bookSearch = '';
            $tagSearch = '';
        }

        $booksQuery = Book::with(['library', 'tags']);

        if ($bookSearch !== '') {
            if (mb_strlen($bookSearch) < 3) {
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

        $books = $booksQuery
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(8)
            ->withQueryString();
        $allBooksCount = $bookSearch === '' ? $books->total() : Book::count();

        if ($request->expectsJson()) {
            return response()->json([
                'html' => view('books.partials.cards', compact('books'))->render(),
                'next_page_url' => $books->nextPageUrl(),
            ]);
        }

        $user = Auth::user();
        $user->forceFill(['books_notification_viewed_at' => now()])->save();

        $tags = Tag::where('approved', true)->whereNotNull('name') // Избацујемо потпуно празне
            ->where('name', '!=', '')     // Избацујемо празне стрингове
            ->get()
            ->map(function ($tag) {
                $tag->name = trim($tag->name); // Склањамо невидљиве размаке са почетка/краја
                return $tag;
            })
            ->unique('name') // Склањамо дупле тагове ако имају исто име
            ->sortBy('name', SORT_LOCALE_STRING) // Сортирамо азбучно (Ћирилица/Латиница)
            ->values(); // ПРЕСУДНО: Ресетујемо индексе на 0, 1, 2, 3...
        $allTagsCount = $tags->count();

        if ($tagSearch !== '') {
            $normalizedTagSearch = mb_strtolower($tagSearch);
            $tags = $tags->filter(
                fn ($tag) => str_contains(mb_strtolower($tag->name), $normalizedTagSearch)
            )->values();
        }

        $activePanel = $panel
            ?? ($bookSearch !== '' ? 'latest-books' : ($tagSearch !== '' ? 'all-tags' : null));
        $activeCard = $panel
            ?? ($bookSearch !== '' ? 'search-books' : ($tagSearch !== '' ? 'search-tags' : null));

        return view('home', [
            'books' => $books,
            'tags' => $tags,
            'allBooksCount' => $allBooksCount,
            'allTagsCount' => $allTagsCount,
            'bookSearch' => $bookSearch,
            'tagSearch' => $tagSearch,
            'bookSearchTooShort' => $bookSearch !== '' && mb_strlen($bookSearch) < 3,
            'activePanel' => $activePanel,
            'activeCard' => $activeCard,
        ]);
    }
}
