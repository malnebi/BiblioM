<?php

use App\Models\Library;
use App\Models\Book;
use App\Models\User;

it('loads books progressively instead of using a show-more control', function () {
    $user = User::factory()->create(['approved' => true]);
    $library = Library::factory()->create(['owner_id' => $user->id]);
    Book::factory()->count(7)->create([
        'library_id' => $library->id,
        'loan' => 0,
    ]);

    $this->actingAs($user)
        ->get('/home')
        ->assertOk()
        ->assertSee('data-scroll-list', false)
        ->assertSee('data-scroll-sentinel', false)
        ->assertDontSee('Види више...')
        ->assertDontSee('Прикажи мање');
});
