<?php

use App\Models\Book;
use App\Models\Library;
use App\Models\Tag;
use App\Models\User;

it('loads book cards progressively on the home and books pages', function () {
    $user = User::factory()->create(['approved' => true]);
    $library = Library::factory()->create(['owner_id' => $user->id]);

    foreach (range(1, 7) as $number) {
        Book::factory()->create([
            'library_id' => $library->id,
            'title' => "Book {$number}",
            'created_at' => now()->subDays(7 - $number),
            'loan' => 0,
        ]);
    }

    $this->actingAs($user)
        ->get('/home')
        ->assertOk()
        ->assertSee('id="latest-books" data-home-panel data-panel-color="blue" hidden', false)
        ->assertSee('id="all-tags" data-home-panel data-panel-color="emerald"', false)
        ->assertSee('data-home-panel-toggle="latest-books"', false)
        ->assertSee('data-home-panel-toggle="all-tags"', false);

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
    $this->get($libraryBooksUrl)
        ->assertOk()
        ->assertSee('Book 7')
        ->assertDontSee('Book 1')
        ->assertSee('href="#page-top"', false)
        ->assertDontSee('Види више...');

    expect($this->getJson("{$libraryBooksUrl}?page=2")->json('html'))->toContain('Book 1');
});

it('shows separate book and tag searches on the home page and returns matching tags', function () {
    $user = User::factory()->create(['approved' => true]);
    Library::factory()->create(['owner_id' => $user->id]);
    Tag::factory()->create(['name' => 'Наука', 'approved' => true]);

    $this->actingAs($user)
        ->get('/home')
        ->assertOk()
        ->assertSee('Претрага књига')
        ->assertSee('Све књиге у апликацији')
        ->assertSee('Претрага описних ознака')
        ->assertSee('Описне ознаке')
        ->assertSee('action="/search"', false)
        ->assertSee('data-home-panel-toggle="latest-books"', false)
        ->assertSee('data-home-panel-toggle="all-tags"', false)
        ->assertSee('aria-pressed="false"', false)
        ->assertSee('action="/search-tag"', false)
        ->assertSee('name="q"', false)
        ->assertSee('id="all-tags"', false)
        ->assertSee('Наука');

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
