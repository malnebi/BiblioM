<?php

use App\Models\Book;
use App\Models\Library;
use App\Models\Tag;

it('belongs to a library', function () {
   $library = Library::factory()->create();
   $book = Book::factory()->create([
      'library_id' => $library->id,
   ]);

   expect($book->library->is($library))->toBeTrue();
});

it('can have tags', function () {
   $book = Book::factory()->create();
   Tag::create(['name' => 'Фантазија', 'approved' => true]);

   $book->tag('Фантазија');

   expect($book->tags)->toHaveCount(1);
});

it('does not create or attach an unapproved tag', function () {
   $book = Book::factory()->create();

   $book->tag('Нова ознака');

   expect($book->tags)->toHaveCount(0);
   $this->assertDatabaseMissing('tags', ['name' => 'Нова ознака']);
});