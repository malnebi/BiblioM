<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Tag;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()   //  stranica sa svim knjigama u navigaciji je pod Zajednica
    {
        $books = Book::latest()->with(['library', 'tags'])->get();  // use eager loading


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

        return view('home', [
            'books' => $books,
            'tags' => $tags,
            'limit' => 5
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
            $library = Auth::user()->ownLibrary;

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


        return view(
            'books.create',
            ['tags' => $tags, 'library' => $library]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $library = Auth::user()->ownLibrary;
        $usesAutomaticNumbering = $library->numbering_mode === 'automatic';

        // 1. Валидација (пази на имена поља из наше компоненте)
        $attributes = $request->validate([
            'lib_book_id' => [
                Rule::requiredIf(!$usesAutomaticNumbering),
                'nullable',
                'integer',
                'min:1',
                Rule::unique('books', 'lib_book_id')->where(
                    fn ($query) => $query->where('library_id', $library->id)
                ),
            ],
            'author_fname' => ['required', 'string', 'max:50'],
            'author_lname' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:200'],
            'publisher_name' => ['required', 'string', 'max:100'],
            'publisher_place' => ['required', 'string', 'max:100'],
            'year' => ['required', 'integer', 'min:1000', 'max:' . (date('Y') + 1)],
            'book_cover' => ['nullable', 'image', 'max:2048'],
            // Овде валидирамо ID-еве за падајуће меније
            'tag1' => ['nullable', 'exists:tags,id,approved,1'],
            'tag2' => ['nullable', 'exists:tags,id,approved,1'],
            'tag3' => ['nullable', 'exists:tags,id,approved,1'],
            // Овде валидирамо текст за нову ознаку
            'suggested_tag' => ['nullable', 'string', 'max:30'],
        ]);

        // Снимање слике
        $imagePath = $request->hasFile('book_cover')
            ? $request->file('book_cover')->store('book_covers', 'public')
            : null;

        // 2. Креирање књиге
        $book = $library->books()->create([
            'lib_book_id' => $usesAutomaticNumbering
                ? (($library->books()->max('lib_book_id') ?? 0) + 1)
                : $attributes['lib_book_id'],
            'author_fname' => $attributes['author_fname'],
            'author_lname' => $attributes['author_lname'],
            'title' => $attributes['title'],
            'publisher_name' => $attributes['publisher_name'],
            'publisher_place' => $attributes['publisher_place'],
            'year' => $attributes['year'],
            'book_cover' => $imagePath,
            'loan' => 0,
        ]);

        // 3. ПОВЕЗИВАЊЕ ОЗНАКА (Pivot логика)

        // А) Повезивање изабраних ознака преко ID-а (attach)
        $selectedTags = collect([$request->tag1, $request->tag2, $request->tag3])->filter();

        if ($selectedTags->isNotEmpty()) {
            $book->tags()->syncWithoutDetaching($selectedTags);
        }

        $suggestionMessage = null;
        if ($request->filled('suggested_tag')) {
            $suggestionMessage = $this->suggestTag($book, $request->suggested_tag);
        }

        $message = 'Књига је успешно додата у библиотеку!';
        if ($suggestionMessage) {
            $message .= ' ' . $suggestionMessage;
        }

        return redirect('/books')->with('success', $message);
    }
    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //     $numberOfLoans = Loan::where('loans.book_id','=', $id )->count(); 
        //     $clientName=Loan::where([['book_id','=', $id]])->get(); // ime klienta koji je poyajmio knjigu

        if (!$book) {
            abort(404);
        }

        $isReservedByMe = $book->isReservedBy(Auth::id());
        $isBorrowedByMe = $book->isBorrowedByMe(Auth::id());
        $data = [

            'book' => $book,
            'isReservedByMe' => $isReservedByMe,
            'isBorrowedByMe' => $isBorrowedByMe,
            //            'numberOfLoans' => $numberOfLoans,
            //            'clientName' => $clientName,  
        ];
        return view('books.book', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
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


        $data = [
            'tags' => $tags,
            'book' => $book,
            'selectedTagIds' => $book->tags()
                ->where('approved', true)
                ->orderBy('tags.id')
                ->limit(3)
                ->pluck('tags.id')
                ->all(),
        ];
        return view('books.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */


    public function update(Request $request, Book $book)
    {
        $attributes = $request->validate([
            'lib_book_id' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('books', 'lib_book_id')
                    ->where(fn ($query) => $query->where('library_id', $book->library_id))
                    ->ignore($book->id),
            ],
            'author_fname' => ['required', 'string', 'max:50'],
            'author_lname' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:200'],
            'publisher_name' => ['required', 'string', 'max:100'],
            'publisher_place' => ['required', 'string', 'max:100'],
            'year' => ['required', 'string'],
            'book_cover' => ['nullable', 'image', 'max:2048'],
            'tag1' => ['nullable', 'exists:tags,id,approved,1'],
            'tag2' => ['nullable', 'exists:tags,id,approved,1'],
            'tag3' => ['nullable', 'exists:tags,id,approved,1'],
            'suggested_tag' => ['nullable', 'string', 'max:30'],
        ]);

        // Обрада нове слике ако је послата
        if ($request->hasFile('book_cover')) {

            // Обриши стару слику ако постоји

            Storage::disk('public')->delete($book->book_cover);

            $attributes['book_cover'] = $request->file('book_cover')->store('book_covers', 'public');
        }

        // Ажурирање података у бази
        $book->update(Arr::except($attributes, ['tag1', 'tag2', 'tag3', 'suggested_tag']));

        // Освежавање ознака (Tags)
        $tags = collect([$request->tag1, $request->tag2, $request->tag3])->filter();
        $book->tags()->sync($tags);

        $suggestionMessage = $request->filled('suggested_tag')
            ? $this->suggestTag($book, $request->suggested_tag)
            : null;

        $message = 'Подаци су успешно измењени!';
        if ($suggestionMessage) {
            $message .= ' ' . $suggestionMessage;
        }

        return redirect('/books/' . $book->id . '/edit')->with('success', $message);
    }

    private function suggestTag(Book $book, string $name): ?string
    {
        $name = trim($name);
        if ($name === '') {
            return null;
        }

        $existingTag = Tag::whereRaw('LOWER(name) = ?', [mb_strtolower($name)])->first();
        if ($existingTag) {
            if ($existingTag->approved) {
                $book->tags()->syncWithoutDetaching([$existingTag->id]);

                return 'Ова ознака већ постоји и додата је књизи.';
            }

            return 'Исти приједлог већ чека одобрење администратора.';
        }

        Tag::create([
            'name' => $name,
            'approved' => false,
            'book_id' => $book->id,
            'suggested_by' => Auth::id(),
        ]);

        return 'Предлог нове ознаке је прослијеђен администратору на одобрење.';
    }

    public function bookReservation(Request $request, Book $book)
    {

        $loans = Loan::where([
            ['user_id', '=', Auth::user()->id],
            ['book_id', '=', $book->id],
            ['library_id', '=', $book->library_id],
            ['description', '=', 'rezervisano']
        ])->first();


        if ($loans) {

            return redirect()->action([BookController::class, 'show'], [$book->id])->with('error', 'Knjiga je već rezervisana ili pozajmljena.');
        }
        $loan = new Loan;
        $loan->user_id = Auth::user()->id;
        $loan->book_id = $book->id;
        $loan->library_id = $book->library_id;
        $loan->description = 'rezervisano';
        $loan->active = $request->active;
        $loan->save();


        return redirect()->action([UserController::class, 'showLoggedUser'], [$loan->user_id]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return redirect('books');
    }
}
