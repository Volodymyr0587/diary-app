<?php

use App\Models\Entry;
use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

it('calculates mood percentages correctly', function () {
    $user = User::factory()->create();

    Entry::factory()->count(3)->create([
        'user_id' => $user->id,
        'mood' => 'happy',
    ]);

    Entry::factory()->count(1)->create([
        'user_id' => $user->id,
        'mood' => 'sad',
    ]);

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));

    $response->assertViewHas('moodPercentages', function ($data) {
        return $data['happy'] == 75 && $data['sad'] == 25;
    });
});

it('shows empty state when no entries', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertSee('There are not enough records to analyze yet');
});