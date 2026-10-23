<?php

use App\Models\Library;
use App\Models\User;

it('shows the back-to-top button on the books and community pages only', function () {
    $user = User::factory()->create(['approved' => true]);
    Library::factory()->create(['owner_id' => $user->id]);

    $this->actingAs($user)
        ->get('/books')
        ->assertOk()
        ->assertSee('href="#page-top"', false)
        ->assertDontSee('hidden h-12 w-12', false)
        ->assertSee('Повратак на врх странице');

    $this->get('/home')
        ->assertOk()
        ->assertSee('href="#page-top"', false);

    $this->get("/library/libraryBooks/{$user->ownLibrary->id}")
        ->assertOk()
        ->assertSee('href="#page-top"', false);

    $this->get('/users')
        ->assertOk()
        ->assertSee('href="#page-top"', false);

    $this->get('/privacy')
        ->assertOk()
        ->assertDontSee('data-back-to-top', false);
});
