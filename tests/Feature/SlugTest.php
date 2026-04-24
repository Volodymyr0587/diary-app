<?php

use App\Models\Entry;
use App\Models\User;

it('generates slug from title', function () {
    $user = User::factory()->create();

    $entry = Entry::create([
        'user_id' => $user->id,
        'title' => 'My Cool Title',
        'content' => 'Some content',
    ]);

    expect($entry->slug)->toBe('my-cool-title');
});

it('generates slug from content when title is null', function () {
    $user = User::factory()->create();

    $entry = Entry::create([
        'user_id' => $user->id,
        'title' => null,
        'content' => 'This is my content text for slug generation',
    ]);

    expect($entry->slug)->toStartWith('this-is-my-content-text');
});

it('limits slug to 8 words from content', function () {
    $user = User::factory()->create();

    $entry = Entry::create([
        'user_id' => $user->id,
        'title' => null,
        'content' => 'one two three four five six seven eight nine ten',
    ]);

    expect($entry->slug)->toBe('one-two-three-four-five-six-seven-eight');
});

it('generates unique slug when duplicate exists', function () {
    $user = User::factory()->create();

    Entry::create([
        'user_id' => $user->id,
        'title' => 'Same Title',
        'content' => '...',
    ]);

    $second = Entry::create([
        'user_id' => $user->id,
        'title' => 'Same Title',
        'content' => '...',
    ]);

    expect($second->slug)->toBe('same-title-1');
});

it('increments slug suffix for multiple duplicates', function () {
    $user = User::factory()->create();

    Entry::factory()->count(3)->create([
        'user_id' => $user->id,
        'title' => 'Same Title',
    ]);

    $entry = Entry::create([
        'user_id' => $user->id,
        'title' => 'Same Title',
        'content' => '...',
    ]);

    expect($entry->slug)->toBe('same-title-3');
});

it('falls back to random slug if base is empty', function () {
    $user = User::factory()->create();

    $entry = Entry::create([
        'user_id' => $user->id,
        'title' => null,
        'content' => '<p>!!!</p>',
    ]);

    expect($entry->slug)->toStartWith('entry-');
});

it('updates slug when title changes', function () {
    $user = User::factory()->create();

    $entry = Entry::create([
        'user_id' => $user->id,
        'title' => 'Old Title',
        'content' => '...',
    ]);

    $entry->update([
        'title' => 'New Title',
    ]);

    expect($entry->fresh()->slug)->toBe('new-title');
});

it('does not change slug if nothing relevant changed', function () {
    $user = User::factory()->create();

    $entry = Entry::create([
        'user_id' => $user->id,
        'title' => 'Stable Title',
        'content' => '...',
    ]);

    $originalSlug = $entry->slug;

    $entry->update([
        'mood' => 'happy',
    ]);

    expect($entry->fresh()->slug)->toBe($originalSlug);
});

it('keeps slug unique when updating to existing title', function () {
    $user = User::factory()->create();

    Entry::create([
        'user_id' => $user->id,
        'title' => 'First Title',
        'content' => '...',
    ]);

    $entry = Entry::create([
        'user_id' => $user->id,
        'title' => 'Second Title',
        'content' => '...',
    ]);

    $entry->update([
        'title' => 'First Title',
    ]);

    expect($entry->fresh()->slug)->toBe('first-title-1');
});