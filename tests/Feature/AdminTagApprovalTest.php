<?php

use App\Models\Book;
use App\Models\Library;
use App\Models\Tag;
use App\Models\User;

it('stores a suggested tag as pending without attaching it to the book', function () {
    $user = User::factory()->create();
    $library = Library::factory()->create([
        'owner_id' => $user->id,
        'numbering_mode' => 'automatic',
    ]);

    $response = $this->actingAs($user)->post('/books', [
        'author_fname' => 'Иво',
        'author_lname' => 'Андрић',
        'title' => 'На Дрини ћуприја',
        'publisher_name' => 'Издавач',
        'publisher_place' => 'Београд',
        'year' => '1945',
        'suggested_tag' => 'Историјски роман',
    ]);

    $response->assertRedirect('/books');
    $book = Book::firstOrFail();
    $tag = Tag::where('name', 'Историјски роман')->firstOrFail();

    expect($tag->approved)->toBeFalse();
    expect($tag->book_id)->toBe($book->id);
    $this->assertDatabaseMissing('book_tag', [
        'book_id' => $book->id,
        'tag_id' => $tag->id,
    ]);
});

it('shows blank selectors and approved tags on the create form', function () {
    $user = User::factory()->create();
    Library::factory()->create(['owner_id' => $user->id]);
    $approvedTag = Tag::factory()->create(['approved' => true]);
    $pendingTag = Tag::factory()->create(['approved' => false]);

    $response = $this->actingAs($user)->get('/books/create');

    $response->assertOk()
        ->assertSee('Опис књиге')
        ->assertSee('value="' . $approvedTag->id . '"', false)
        ->assertDontSee('value="' . $pendingTag->id . '"', false)
        ->assertDontSee('Изаберите прву ознаку')
        ->assertDontSee('Изаберите другу ознаку')
        ->assertDontSee('Изаберите трећу ознаку');
});

it('stores a suggested tag as pending when updating a book', function () {
    $user = User::factory()->create();
    Library::factory()->create(['owner_id' => $user->id]);
    $book = Book::factory()->create();
    $newInventoryNumber = $book->lib_book_id + 1;

    $response = $this->actingAs($user)->followingRedirects()->post('/books/' . $book->id, [
        '_method' => 'PUT',
        'lib_book_id' => $newInventoryNumber,
        'author_fname' => 'Измијењено име',
        'author_lname' => 'Измијењено презиме',
        'title' => 'Нови наслов књиге',
        'publisher_name' => 'Нови издавач',
        'publisher_place' => 'Ново мјесто',
        'year' => '2025',
        'suggested_tag' => 'Предлог из измјене',
    ]);

    $response->assertOk()
        ->assertSee('value="Нови наслов књиге"', false)
        ->assertSee('прослијеђен администратору');
    $this->assertDatabaseHas('books', [
        'id' => $book->id,
        'lib_book_id' => $newInventoryNumber,
        'author_fname' => 'Измијењено име',
        'author_lname' => 'Измијењено презиме',
        'title' => 'Нови наслов књиге',
        'publisher_name' => 'Нови издавач',
        'publisher_place' => 'Ново мјесто',
        'year' => '2025',
    ]);
    $tag = Tag::where('name', 'Предлог из измјене')->firstOrFail();

    expect($tag->approved)->toBeFalse();
    expect($tag->book_id)->toBe($book->id);
});

it('renders the edit form with a browser-compatible PUT method', function () {
    $book = Book::factory()->create();

    $response = $this->actingAs($book->library->owner)->get('/books/' . $book->id . '/edit');

    $response->assertOk()
        ->assertSee('method="POST"', false)
        ->assertSee('name="_method" value="PUT"', false)
        ->assertSee('name="lib_book_id"', false)
        ->assertDontSee('readonly', false)
        ->assertSee('name="suggested_tag"', false)
        ->assertDontSee('name="_method" value="POST"', false);
});

    it('preselects the book tags in the edit form', function () {
        $book = Book::factory()->create();
        $tags = Tag::factory()->count(2)->create(['approved' => true]);
        $book->tags()->attach($tags->pluck('id'));

        $response = $this->actingAs($book->library->owner)->get('/books/' . $book->id . '/edit');

        $response->assertOk()
        ->assertSee('Изаберите ознаке за књигу')
        ->assertSee('value="' . $tags[0]->id . '" selected', false)
        ->assertSee('value="' . $tags[1]->id . '" selected', false)
        ->assertSee('<option value="" selected></option>', false);
    });

it('explains when the same tag is already waiting for approval', function () {
    $book = Book::factory()->create();
    Tag::create([
        'name' => 'Већ предложена ознака',
        'approved' => false,
        'book_id' => $book->id,
    ]);

    $response = $this->actingAs(User::factory()->create())->post('/books/' . $book->id, [
        '_method' => 'PUT',
        'lib_book_id' => $book->lib_book_id,
        'author_fname' => $book->author_fname,
        'author_lname' => $book->author_lname,
        'title' => $book->title,
        'publisher_name' => $book->publisher_name,
        'publisher_place' => $book->publisher_place,
        'year' => $book->year,
        'suggested_tag' => 'Већ предложена ознака',
    ]);

    $response->assertSessionHas('success', fn ($message) => str_contains($message, 'већ чека одобрење'));
    $this->assertDatabaseCount('tags', 1);
});

it('shows pending tag suggestions on the admin dashboard', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['role' => 'admin'])->save();
    Library::factory()->create(['owner_id' => $admin->id]);
    Tag::create(['name' => 'Научна фантастика', 'approved' => false]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk()->assertSee('Научна фантастика');
});

it('approves a suggested tag and attaches it to its book', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['role' => 'admin'])->save();
    $book = Book::factory()->create();
    $tag = Tag::create([
        'name' => 'Историја',
        'approved' => false,
        'book_id' => $book->id,
    ]);

    $response = $this->actingAs($admin)->patch(route('admin.tags.approve', $tag));

    $response->assertRedirect();
    expect($tag->fresh()->approved)->toBeTrue();
    $this->assertDatabaseHas('book_tag', [
        'book_id' => $book->id,
        'tag_id' => $tag->id,
    ]);
});

it('lets admins correct a pending tag name before approving it', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['role' => 'admin'])->save();
    $book = Book::factory()->create();
    $tag = Tag::create([
        'name' => 'Стари назив',
        'approved' => false,
        'book_id' => $book->id,
    ]);

    $response = $this->actingAs($admin)->patch(route('admin.tags.update-name', $tag), [
        'name' => 'Исправљени назив',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('tags', [
        'id' => $tag->id,
        'name' => 'Исправљени назив',
        'approved' => false,
    ]);

    $this->actingAs($admin)->patch(route('admin.tags.approve', $tag->fresh()));

    expect($tag->fresh()->approved)->toBeTrue();
    $this->assertDatabaseHas('book_tag', [
        'book_id' => $book->id,
        'tag_id' => $tag->id,
    ]);
});

it('prevents admins from renaming a pending tag to an existing tag name', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['role' => 'admin'])->save();
    $tag = Tag::create(['name' => 'Предлог', 'approved' => false]);
    Tag::create(['name' => 'Постојећа ознака', 'approved' => true]);

    $response = $this->actingAs($admin)->patch(route('admin.tags.update-name', $tag), [
        'name' => 'Постојећа ознака',
    ]);

    $response->assertSessionHasErrors('name');
    $this->assertDatabaseHas('tags', ['id' => $tag->id, 'name' => 'Предлог']);
});

it('rejects a suggested tag by removing it', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['role' => 'admin'])->save();
    $tag = Tag::create(['name' => 'Неприкладана ознака', 'approved' => false]);

    $response = $this->actingAs($admin)->delete(route('admin.tags.reject', $tag));

    $response->assertRedirect();
    $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
});