<?php

use App\Models\Book;
use App\Models\Library;
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
        ->assertSee('Book 7')
        ->assertSee('Book 2')
        ->assertDontSee('Book 1')
        ->assertSee('data-scroll-sentinel', false);

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
