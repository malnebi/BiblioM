<?php

use App\Models\Book;
use App\Models\Library;
use App\Models\User;

it('shows author initials and title when a book has no cover image', function () {
    $owner = User::factory()->create();
    $library = Library::factory()->create(['owner_id' => $owner->id]);
    $book = Book::factory()->create([
        'library_id' => $library->id,
        'author_fname' => 'Иво',
        'author_lname' => 'Андрић',
        'title' => 'На Дрини ћуприја',
        'book_cover' => null,
    ]);

    $this->get("/libraries/{$library->id}")
        ->assertOk()
        ->assertSee('И.А.')
        ->assertSee('На Дрини ћуприја')
        ->assertSee('line-clamp-3', false)
        ->assertSee('text-[6px]', false)
        ->assertSee('sm:text-[7px]', false)
        ->assertSee('data-book-cover-placeholder', false);

    expect($book->book_cover)->toBeNull();
});

it('shows the fallback cover in book forms and keeps the upload control available', function () {
    $owner = User::factory()->create();
    $library = Library::factory()->create(['owner_id' => $owner->id]);
    $book = Book::factory()->create([
        'library_id' => $library->id,
        'author_fname' => 'Иво',
        'author_lname' => 'Андрић',
        'title' => 'На Дрини ћуприја',
        'book_cover' => null,
    ]);

    $this->actingAs($owner)
        ->get('/books/create')
        ->assertOk()
        ->assertSee('data-book-cover-placeholder', false)
        ->assertSee('id="book_cover_input"', false);

    $this->get("/books/{$book->id}/edit")
        ->assertOk()
        ->assertSee('И.А.')
        ->assertSee('На Дрини ћуприја')
        ->assertSee('id="book_cover_input"', false);
});
