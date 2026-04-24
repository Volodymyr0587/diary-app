<?php

use App\Models\Entry;
use App\Models\User;
use Livewire\Livewire;

it('shows only user entries on index page', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $entry = Entry::factory()->create([
        'user_id' => $user->id,
        'title' => 'My Entry',
    ]);

    Entry::factory()->create([
        'user_id' => $otherUser->id,
        'title' => 'Other Entry',
    ]);

    Livewire::actingAs($user)
        ->test('pages::entry.index')
        ->assertSee('My Entry')
        ->assertDontSee('Other Entry');
});

it('can create entry', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('pages::entry.create')
        ->set('form.title', 'Test Entry')
        ->set('form.content', 'Test Content')
        ->set('form.mood', 'happy')
        ->call('save');

    $this->assertDatabaseHas('entries', [
        'title' => 'Test Entry',
        'user_id' => $user->id,
    ]);
});

it('validates required fields on create', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('pages::entry.create')
        ->call('save')
        ->assertHasErrors(['form.content']);
});

it('validates in: rule for mood field on create', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('pages::entry.create')
        ->set('form.title', 'Test Entry')
        ->set('form.content', 'Test Content')
        ->set('form.mood', 'not existing mood')
        ->call('save')
        ->assertHasErrors(['form.mood']);
});

it('can view entry by slug', function () {
    $user = User::factory()->create();

    $entry = Entry::factory()->create([
        'user_id' => $user->id,
        'title' => 'My Entry',
        'slug' => 'my-entry',
    ]);

    $this->actingAs($user)
        ->get(route('entries.show', $entry))
        ->assertSee('My Entry');
});

it('cannot view someone else\'s entry', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $entry = Entry::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    $this->actingAs($user)
        ->get(route('entries.show', $entry))
        ->assertNotFound();
});

it('can update entry', function () {
    $user = User::factory()->create();

    $entry = Entry::factory()->create([
        'user_id' => $user->id,
        'title' => 'Old Title',
    ]);

    Livewire::actingAs($user)
        ->test('pages::entry.edit', ['entry' => $entry])
        ->set('form.title', 'Updated Title')
        ->call('update')
        ->assertHasNoErrors();

    expect($entry->fresh()->title)->toBe('Updated Title');
});

it('can delete entry', function () {
    $user = User::factory()->create();

    $entry = Entry::factory()->create([
        'user_id' => $user->id,
    ]);

    Livewire::actingAs($user)
        ->test('pages::entry.show', ['entry' => $entry])
        ->call('delete');

    $this->assertSoftDeleted('entries', [
        'id' => $entry->id,
    ]);
});

it('shows only trashed entries', function () {
    $user = User::factory()->create();

    $entry = Entry::factory()->create([
        'user_id' => $user->id,
        'title' => 'Deleted Entry',
    ]);

    $entry->delete();

    Livewire::actingAs($user)
        ->test('pages::entry.trash')
        ->assertSee('Deleted Entry');
});

it('can restore entry', function () {
    $user = User::factory()->create();

    $entry = Entry::factory()->create([
        'user_id' => $user->id,
    ]);

    $entry->delete();

    Livewire::actingAs($user)
        ->test('pages::entry.trash')
        ->call('restore', $entry->id);

    expect($entry->fresh()->trashed())->toBeFalse();
});

it('can permanently delete entry', function () {
    $user = User::factory()->create();

    $entry = Entry::factory()->create([
        'user_id' => $user->id,
    ]);

    $entry->delete();

    Livewire::actingAs($user)
        ->test('pages::entry.trash')
        ->call('forceDelete', $entry->id);

    $this->assertDatabaseMissing('entries', [
        'id' => $entry->id,
    ]);
});