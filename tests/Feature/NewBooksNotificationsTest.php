<?php

use App\Models\Book;
use App\Models\Library;
use App\Models\User;

it('shows the number of books added since each user last visited home', function () {
    $viewedAt = now()->subHour();
    $library = Library::factory()->create();

    $user = User::factory()->create();
    $user->forceFill(['books_notification_viewed_at' => $viewedAt])->save();

    $otherUser = User::factory()->create();
    $otherUser->forceFill(['books_notification_viewed_at' => now()])->save();

    Book::factory()->create([
        'library_id' => $library->id,
        'created_at' => $viewedAt->copy()->subMinute(),
    ]);
    Book::factory()->count(2)->create([
        'library_id' => $library->id,
        'created_at' => $viewedAt->copy()->addMinutes(10),
    ]);

    $this->actingAs($user)
        ->get('/privacy')
        ->assertOk()
        ->assertSee('data-notification-count="new-books"', false)
        ->assertSee('data-count="2"', false);

    $this->actingAs($user)
        ->get('/home')
        ->assertOk()
        ->assertDontSee('data-notification-count="new-books"', false);

    $this->actingAs($user)
        ->get('/privacy')
        ->assertOk()
        ->assertDontSee('data-notification-count="new-books"', false);

    $this->actingAs($otherUser)
        ->get('/privacy')
        ->assertOk()
        ->assertDontSee('data-notification-count="new-books"', false);
});
