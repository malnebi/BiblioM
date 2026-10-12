<?php

use App\Models\Library;
use App\Models\User;

it('loads community library cards in pages when requested', function () {
    foreach (range(1, 13) as $number) {
        $user = User::factory()->create([
            'name' => "Community member {$number}",
            'approved' => true,
            'created_at' => now()->subDays(13 - $number),
        ]);

        Library::factory()->create(['owner_id' => $user->id]);
    }

    $response = $this->get('/users');

    $response->assertOk()
        ->assertSee('data-next-page="', false)
        ->assertSee('users?page=2', false)
        ->assertSeeInOrder(['Community member 13', 'Community member 12']);
    $this->assertDoesNotMatchRegularExpression('/>\s*Community member 1\s*<\/h3>/', $response->getContent());

    $firstPage = $this->getJson('/users?page=2');

    $firstPage->assertOk()
        ->assertJsonStructure(['html', 'next_page_url'])
        ->assertJsonPath('next_page_url', null);

    expect($firstPage->json('html'))
        ->toContain('Community member 1');
    $this->assertMatchesRegularExpression('/>\s*Community member 1\s*<\/h3>/', $firstPage->json('html'));
    expect(substr_count($firstPage->json('html'), 'title="Библиотека"'))->toBe(1);
});
