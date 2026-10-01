<?php

use App\Models\Book;
use App\Models\Library;
use App\Models\Loan;
use App\Models\User;

it('shows member school role and profile link on loan pages', function () {
    $libraryOwner = User::factory()->create();
    $library = Library::factory()->create(['owner_id' => $libraryOwner->id]);
    $member = User::factory()->create([
        'name' => 'Ана',
        'last_name' => 'Јовановић',
        'role_type' => 'Ученик',
        'role_details' => 'III-2',
    ]);
    $book = Book::factory()->create([
        'library_id' => $library->id,
        'lib_user_id' => $member->id,
        'loan' => 1,
    ]);

    $loan = new Loan();
    $loan->user_id = $member->id;
    $loan->book_id = $book->id;
    $loan->library_id = $library->id;
    $loan->active = 1;
    $loan->description = 'potpisano';
    $loan->save();

    $this->actingAs($libraryOwner)
        ->get('/users/' . $member->id)
        ->assertOk()
        ->assertDontSee('Улога у школи:')
        ->assertSee('Ученик · III-2');

    $this->actingAs($libraryOwner)
        ->get('/loans/myLibraryLoans/' . $library->id)
        ->assertOk()
        ->assertSee('href="/users/' . $member->id . '"', false)
        ->assertSee('Ана Јовановић')
        ->assertSee('Ученик · III-2');
});