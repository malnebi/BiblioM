<?php

use App\Models\Book;
use App\Models\Library;
use App\Models\Loan;
use App\Models\User;

it('shows pickup notifications until the member confirms the book handover', function () {
    $libraryOwner = User::factory()->create();
    $library = Library::factory()->create(['owner_id' => $libraryOwner->id]);
    $member = User::factory()->create();
    $book = Book::factory()->create([
        'library_id' => $library->id,
        'lib_user_id' => $member->id,
        'loan' => 1,
    ]);

    $loan = Loan::create([
        'user_id' => $member->id,
        'book_id' => $book->id,
        'library_id' => $library->id,
        'active' => 1,
        'description' => null,
    ]);

    $this->actingAs($member)
        ->get('/loggedUser/'.$member->id)
        ->assertOk()
        ->assertSee('data-notification-count="pickup"', false);

    $otherMember = User::factory()->create();
    $this->actingAs($otherMember)
        ->put('/loans/loanBookMemberConfirm/'.$loan->id)
        ->assertForbidden();

    $this->actingAs($libraryOwner)
        ->get('/users/'.$member->id)
        ->assertOk()
        ->assertSee('data-notification-count="library-confirmation"', false)
        ->assertSee('Књигу предајте кориснику тек након што он потврди преузимање.');

    $this->actingAs($libraryOwner)
        ->get('/loans/myLibraryLoans/'.$library->id)
        ->assertOk()
        ->assertDontSee('id="sec-preuzimanje" class="content-sec hidden', false)
        ->assertSee('data-notification-count="library-confirmation"', false);

    expect($loan->fresh()->library_confirmation_viewed_at)->toBeNull();

    $this->actingAs($member)
        ->put('/loans/loanBookMemberConfirm/'.$loan->id)
        ->assertRedirect('/loggedUser/'.$member->id);

    expect($loan->fresh()->description)->toBe('potpisano');

    $this->actingAs($member)
        ->get('/loggedUser/'.$member->id)
        ->assertOk()
        ->assertDontSee('data-notification-count="pickup"', false);

    $this->actingAs($libraryOwner)
        ->get('/users/'.$member->id)
        ->assertOk()
        ->assertDontSee('data-notification-count="library-confirmation"', false);
});

it('does not allow a different library to confirm a reservation', function () {
    $owner = User::factory()->create();
    $library = Library::factory()->create(['owner_id' => $owner->id]);
    $otherOwner = User::factory()->create();
    Library::factory()->create(['owner_id' => $otherOwner->id]);
    $member = User::factory()->create();
    $book = Book::factory()->create(['library_id' => $library->id]);

    $loan = Loan::create([
        'user_id' => $member->id,
        'book_id' => $book->id,
        'library_id' => $library->id,
        'active' => null,
        'description' => 'rezervisano',
    ]);

    $this->actingAs($otherOwner)
        ->put('/loans/loanLibraryConfirm/'.$loan->id)
        ->assertForbidden();

    expect($loan->fresh()->active)->toBeNull()
        ->and($loan->fresh()->description)->toBe('rezervisano');

    $this->actingAs($owner)
        ->put('/loans/loanLibraryConfirm/'.$loan->id)
        ->assertRedirect('/users/'.$member->id);

    expect($loan->fresh()->active)->toBe(1);

    $this->actingAs($member)
        ->get('/loggedUser/'.$member->id)
        ->assertOk()
        ->assertSee('data-notification-count="pickup"', false);

    $this->actingAs($member)
        ->put('/loans/loanBookMemberConfirm/'.$loan->id)
        ->assertRedirect('/loggedUser/'.$member->id);

    expect($book->fresh()->lib_user_id)->toBe($member->id);
});
