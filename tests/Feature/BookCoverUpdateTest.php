<?php

use App\Models\Book;
use App\Models\Library;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('adds a cover to a book that does not have one yet', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $library = Library::factory()->create(['owner_id' => $user->id]);
    $book = Book::factory()->create([
        'library_id' => $library->id,
        'book_cover' => null,
    ]);

    $response = $this->actingAs($user)->put("/books/{$book->id}", [
        'lib_book_id' => $book->lib_book_id,
        'author_fname' => $book->author_fname,
        'author_lname' => $book->author_lname,
        'title' => $book->title,
        'publisher_name' => $book->publisher_name,
        'publisher_place' => $book->publisher_place,
        'year' => $book->year,
        'book_cover' => UploadedFile::fake()->create('cover.jpg', 10, 'image/jpeg'),
    ]);

    $response->assertRedirect("/books/{$book->id}/edit");

    $book->refresh();
    expect($book->book_cover)->not->toBeNull();
    Storage::disk('public')->assertExists($book->book_cover);
});
