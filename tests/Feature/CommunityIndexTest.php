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

    $response = $this->get('/community');

    $response->assertOk()
        ->assertSee('data-next-page="', false)
        ->assertSee('community?page=2', false)
        ->assertSeeInOrder(['Community member 13', 'Community member 12']);
    $this->assertDoesNotMatchRegularExpression('/>\s*Community member 1\s*<\/h3>/', $response->getContent());

    $firstPage = $this->getJson('/community?page=2');

    $firstPage->assertOk()
        ->assertJsonStructure(['html', 'next_page_url']);
    expect($firstPage->json('next_page_url'))->toEndWith('/community?page=3');

    expect($firstPage->json('html'))
        ->toContain('Community member 7')
        ->toContain('Community member 2');
    expect(substr_count($firstPage->json('html'), 'title="Библиотека"'))->toBe(6);

    $lastPage = $this->getJson('/community?page=3');
    $lastPage->assertOk()->assertJsonPath('next_page_url', null);
    $this->assertMatchesRegularExpression('/>\s*Community member 1\s*<\/h3>/', $lastPage->json('html'));
});

it('searches community members and libraries and keeps the search on later pages', function () {
    foreach (range(1, 7) as $number) {
        $user = User::factory()->create([
            'name' => $number === 7 ? 'Mira' : "Community member {$number}",
            'last_name' => $number === 7 ? 'Jovanovic' : "Surname {$number}",
            'approved' => true,
        ]);

        Library::factory()->create([
            'owner_id' => $user->id,
            'name' => $number === 7 ? 'School Library' : "Library {$number}",
        ]);
    }

    $memberSearch = $this->get('/community?q=Mira%20Jovanovic');
    $memberSearch->assertOk()
        ->assertSee('Mira')
        ->assertSee('School Library')
        ->assertDontSee('Community member 1');

    $librarySearch = $this->get('/community?q=School');
    $librarySearch->assertOk()
        ->assertSee('Mira')
        ->assertDontSee('Community member 1');

    foreach (range(8, 20) as $number) {
        $user = User::factory()->create([
            'name' => "Matching member {$number}",
            'approved' => true,
        ]);
        Library::factory()->create([
            'owner_id' => $user->id,
            'name' => "School Library {$number}",
        ]);
    }

    $firstPage = $this->get('/community?q=School');
    $firstPage->assertOk()->assertSee('community?q=School&amp;page=2', false);

    $nextPage = $this->getJson('/community?q=School&page=2');
    $nextPage->assertOk()->assertJsonStructure(['html', 'next_page_url']);
    $this->assertStringContainsString('q=School', $nextPage->json('next_page_url'));
    $this->assertStringContainsString('Matching member', $nextPage->json('html'));
});
