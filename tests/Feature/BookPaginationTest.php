<?php

use App\Models\Book;
use App\Models\Library;
use App\Models\Tag;
use App\Models\User;

it('loads book cards progressively on the home and books pages', function () {
    $user = User::factory()->create(['approved' => true]);
    $library = Library::factory()->create(['owner_id' => $user->id]);

    foreach (range(1, 9) as $number) {
        Book::factory()->create([
            'library_id' => $library->id,
            'title' => "Book {$number}",
            'created_at' => now()->subDays(9 - $number),
            'loan' => 0,
        ]);
    }

    $homePage = $this->actingAs($user)->get('/home');
    $homePage->assertOk()
        ->assertSee('data-home-panel-toggle="latest-books"', false)
        ->assertSee('data-home-panel-toggle="all-tags"', false)
        ->assertSee('href="' . route('home', ['panel' => 'latest-books']) . '"', false)
        ->assertSee('href="' . route('home', ['panel' => 'all-tags']) . '"', false);
    $this->assertMatchesRegularExpression('/data-home-card="search-books"[^>]*class="col-span-3/', $homePage->getContent());
    $this->assertMatchesRegularExpression('/data-home-card="search-tags"[^>]*class="col-span-3/', $homePage->getContent());
    $this->assertMatchesRegularExpression('/data-home-card="latest-books"[^>]*class="group col-span-1/', $homePage->getContent());
    $this->assertMatchesRegularExpression('/data-home-card="all-tags"[^>]*class="group col-span-1/', $homePage->getContent());
    $homePage->assertSee('h-3.5 min-h-0 w-full min-w-0', false)
        ->assertSee('sm:py-1 sm:text-[10px]', false);
    $homePage->assertSee('data-mobile-book-grid', false)
        ->assertSee('grid grid-cols-2 gap-x-2 gap-y-4 sm:gap-x-6 sm:gap-y-12 md:grid-cols-2 lg:grid-cols-4', false)
        ->assertSee('whitespace-nowrap text-[9px]', false)
        ->assertSee('data-book-card', false);
    $this->assertMatchesRegularExpression('/<section id="latest-books"[^>]*\shidden\s*>/', $homePage->getContent());
    $this->assertMatchesRegularExpression('/<section id="all-tags"[^>]*\shidden\s*>/', $homePage->getContent());

    $nextHomePage = $this->getJson('/home?page=2')
        ->assertOk()
        ->assertJsonPath('next_page_url', null);
    expect($nextHomePage->json('html'))->toContain('Book 1');

    $this->get('/books')
        ->assertOk()
        ->assertSee('Book 7')
        ->assertDontSee('Book 1');

    expect($this->getJson('/books?page=2')->json('html'))->toContain('Book 1');

    $libraryBooksUrl = "/library/libraryBooks/{$library->id}";
    $initialLibraryPage = $this->get("/libraries/{$library->id}");
    $initialLibraryPage->assertOk()
        ->assertSee('data-library-header="own"', false)
        ->assertSee('Моја библиотека')
        ->assertDontSee('Укупно књига: 9')
        ->assertSee('data-home-card="search-books"', false)
        ->assertSee('data-home-panel-toggle="all-books"', false)
        ->assertSee('href="' . url("/libraries/{$library->id}") . '?panel=all-books"', false)
        ->assertSee('Све књиге библиотеке')
        ->assertSee('text-[7px] font-bold uppercase leading-tight', false)
        ->assertSee('text-sm font-black text-blue-400', false)
        ->assertDontSee('Претражи наслов, аутора или годину издања...')
        ->assertSee('p-4 shadow-2xl sm:p-8', false)
        ->assertSee('h-3.5 min-h-0 w-full min-w-0', false)
        ->assertSee('data-mobile-book-grid', false)
        ->assertSee('data-scroll-list', false)
        ->assertDontSee('Види више...')
        ->assertSee('grid grid-cols-2 gap-x-2 gap-y-4 sm:gap-x-6 sm:gap-y-12 md:grid-cols-2 lg:grid-cols-4', false)
        ->assertSee('data-book-title', false);
    $this->assertMatchesRegularExpression('/<section id="search-books"[^>]*\shidden\s*>/', $initialLibraryPage->getContent());
    $this->assertMatchesRegularExpression('/<section id="all-books"[^>]*\shidden\s*>/', $initialLibraryPage->getContent());

    $this->get("/libraries/{$library->id}?panel=search-books")
        ->assertOk()
        ->assertSee('Резултати претраге књига (0)')
        ->assertSee('Унесите појам за претрагу књига.');

    $this->get("/libraries/{$library->id}?q=Book%203&panel=search-books")
        ->assertOk()
        ->assertSee('value="Book 3"', false)
        ->assertSee('Резултати претраге књига (1)')
        ->assertSee('Book 3')
        ->assertSee('aria-pressed="true"', false);

    $allBooksLibraryPage = $this->get("/libraries/{$library->id}?q=Book%203&panel=all-books");
    $allBooksLibraryPage->assertOk()
        ->assertSee('Све књиге библиотеке (9)')
        ->assertSee('Book 9')
        ->assertDontSee('Book 1')
        ->assertSee('value=""', false);
    $this->assertMatchesRegularExpression('/<section id="search-books"[^>]*\shidden\s*>/', $allBooksLibraryPage->getContent());
    expect($this->getJson("/libraries/{$library->id}?panel=all-books&page=2")->json('html'))->toContain('Book 1');

    $initialLibraryBooksPage = $this->actingAs($user)->get($libraryBooksUrl);
    $initialLibraryBooksPage->assertOk()
        ->assertSee('data-library-header="own"', false)
        ->assertSee('Моја библиотека')
        ->assertDontSee('Укупно књига: 9')
        ->assertSee('data-home-card="search-books"', false)
        ->assertSee('data-home-panel-toggle="all-books"', false)
        ->assertSee('href="' . url($libraryBooksUrl) . '?panel=all-books"', false)
        ->assertSee('text-[7px] font-bold uppercase leading-tight', false)
        ->assertSee('text-sm font-black text-blue-400', false)
        ->assertSee('flex flex-row items-center gap-3 text-left sm:flex-col', false)
        ->assertSee('h-14 w-14 shrink-0', false)
        ->assertSee('Book 9')
        ->assertDontSee('Book 1')
        ->assertSee('href="#page-top"', false)
        ->assertDontSee('Види више...')
        ->assertSee('data-mobile-book-grid', false)
        ->assertSee('grid grid-cols-2 gap-x-2 gap-y-4 sm:gap-x-6 sm:gap-y-12 md:grid-cols-2 lg:grid-cols-4', false);
    $this->assertMatchesRegularExpression('/<section id="search-books"[^>]*\shidden\s*>/', $initialLibraryBooksPage->getContent());
    $this->assertMatchesRegularExpression('/<section id="all-books"[^>]*\shidden\s*>/', $initialLibraryBooksPage->getContent());

    $this->actingAs($user)->get("{$libraryBooksUrl}?panel=search-books")
        ->assertOk()
        ->assertSee('Резултати претраге књига (0)')
        ->assertSee('Унесите појам за претрагу књига.');

    expect($this->getJson("{$libraryBooksUrl}?page=2")->json('html'))->toContain('Book 1');

    $searchLibraryBooksPage = $this->actingAs($user)->get("{$libraryBooksUrl}?q=Book%203&panel=search-books");
    $searchLibraryBooksPage->assertOk()
        ->assertSee('Резултати претраге књига (1)')
        ->assertSee('Book 3');
    $this->assertMatchesRegularExpression('/<section id="all-books"[^>]*\shidden\s*>/', $searchLibraryBooksPage->getContent());

    $this->actingAs($user)->get("{$libraryBooksUrl}?q=Book%203&panel=all-books")
        ->assertOk()
        ->assertSee('Све књиге библиотеке (9)')
        ->assertSee('value=""', false)
        ->assertSee('Book 7')
        ->assertDontSee('Резултати претраге књига (1)');

    $otherLibrary = Library::factory()->create(['name' => 'Друга библиотека']);
    $this->get("/libraries/{$otherLibrary->id}")
        ->assertOk()
        ->assertSee('data-library-header="public"', false)
        ->assertSee('Библиотека')
        ->assertDontSee('data-library-header="own"', false)
        ->assertDontSee('Укупно књига: 0');
});

it('shows separate book and tag searches on the home page and returns matching tags', function () {
    $user = User::factory()->create(['approved' => true]);
    Library::factory()->create(['owner_id' => $user->id]);
    Tag::factory()->create(['name' => 'Наука', 'approved' => true]);

    $this->actingAs($user)
        ->get('/home')
        ->assertOk()
        ->assertSee('Претрага књига')
        ->assertSee('data-home-card="search-books"', false)
        ->assertSee('Све књиге у апликацији')
        ->assertSee('Претрага описних ознака')
        ->assertSee('data-home-card="search-tags"', false)
        ->assertSee('Описне ознаке')
        ->assertSee(route('home'), false)
        ->assertSee('data-home-panel-toggle="latest-books"', false)
        ->assertSee('data-home-panel-toggle="all-tags"', false)
        ->assertSee('aria-pressed="false"', false)
        ->assertSee('name="q"', false)
        ->assertSee('name="tag_q"', false)
        ->assertSee('id="all-tags"', false)
        ->assertSee('Наука');

    $allTagsPanel = $this->get('/home?panel=all-tags');
    $allTagsPanel->assertOk()
        ->assertSee('Наука');
    $this->assertMatchesRegularExpression('/<section id="all-tags"[^>]*aria-labelledby="all-tags-heading"[^>]*>/', $allTagsPanel->getContent());

    $this->get('/tags')
        ->assertOk()
        ->assertSee('Описне ознаке (1)')
        ->assertSee('Наука');

    $this->get('/search-tag?q=Наука')
        ->assertOk()
        ->assertSee('Резултати претраге описних ознака')
        ->assertSee('Наука');

    $this->get('/search-tag?q=непостојећа')
        ->assertOk()
        ->assertSee('Нема описних ознака за ову претрагу.');
});

it('shows book and tag search results in their selected homepage sections', function () {
    $user = User::factory()->create(['approved' => true]);
    $library = Library::factory()->create(['owner_id' => $user->id, 'name' => 'Градска библиотека']);
    $searchedBook = Book::factory()->create([
        'library_id' => $library->id,
        'title' => 'Потражена књига',
        'publisher_name' => 'Народна књига',
        'publisher_place' => 'Београд',
        'year' => '2024',
        'lib_book_id' => 123,
        'loan' => 0,
    ]);
    $searchedBook->tags()->attach(Tag::factory()->create(['name' => 'Научна', 'approved' => true]));
    Book::factory()->create([
        'library_id' => $library->id,
        'title' => 'Друга књига',
        'loan' => 0,
    ]);
    Tag::factory()->create(['name' => 'Потражена ознака', 'approved' => true]);
    Tag::factory()->create(['name' => 'Друга ознака', 'approved' => true]);

    $bookSearch = $this->actingAs($user)->get('/home?q=Потражена');
    $bookSearch->assertOk()
        ->assertSee('value="Потражена"', false)
        ->assertSee('Резултати претраге књига (1)')
        ->assertSee('Потражена књига')
        ->assertSee('data-book-title', false)
        ->assertSee('line-clamp-2', false)
        ->assertSee('aria-label="Прикажи податке о издавачу, инвентарном броју и библиотеци"', false)
        ->assertSee('Издавач:</strong> Београд: Народна књига, 2024', false)
        ->assertSee('Инв. бр.:</strong> #123', false)
        ->assertSee('Градска библиотека')
        ->assertDontSee('Број у апликацији')
        ->assertDontSee('БИБЛИОТЕКА:')
        ->assertSee('font-light', false)
        ->assertDontSee('Друга књига');
    $this->assertMatchesRegularExpression('/<div data-home-card="search-books"[^>]*aria-pressed="true"/', $bookSearch->getContent());
    $this->assertMatchesRegularExpression('/<a[^>]*data-home-card="latest-books"[^>]*aria-current="false"/', $bookSearch->getContent());
    $this->assertMatchesRegularExpression('/<section id="all-tags"[^>]*\shidden\s*>/', $bookSearch->getContent());

    $tagSearch = $this->get('/home?tag_q=Потражена');
    $tagSearch->assertOk()
        ->assertSee('value="Потражена"', false)
        ->assertSee('Резултати претраге описних ознака (1)')
        ->assertSee('Потражена ознака')
        ->assertDontSee('Друга ознака');
    $this->assertMatchesRegularExpression('/<div data-home-card="search-tags"[^>]*aria-pressed="true"/', $tagSearch->getContent());
    $this->assertMatchesRegularExpression('/<section id="latest-books"[^>]*\shidden\s*>/', $tagSearch->getContent());

    $this->get('/home?q=непостојећа')
        ->assertOk()
        ->assertSee('Нема књига за унесени појам.');

    $this->get('/home?tag_q=непостојећа')
        ->assertOk()
        ->assertSee('Нема описних ознака за ову претрагу.');

    $allBooksPanel = $this->get('/home?q=Потражена&panel=latest-books');
    $allBooksPanel->assertOk()
        ->assertSee('Најновије књиге у апликацији')
        ->assertSee('Потражена књига')
        ->assertSee('Друга књига')
        ->assertDontSee('value="Потражена"', false)
        ->assertSee('aria-current="location"', false);
    $this->assertMatchesRegularExpression('/<a[^>]*data-home-card="latest-books"[^>]*aria-current="location"/', $allBooksPanel->getContent());
});
