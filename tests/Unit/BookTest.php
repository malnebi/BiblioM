<?php

use App\Models\Book;
use App\Models\Library;

it('belongs to a library', function () {
   $library = Library::factory()->create();
   $book = Book::factory()->create([
      'library_id' => $library->id,
   ]);

   expect($book->library->is($library))->toBeTrue();
});

it('can have tags', function () {
   $book = Book::factory()->create();

   $book->tag('Фантазија');

   expect($book->tags)->toHaveCount(1);
});