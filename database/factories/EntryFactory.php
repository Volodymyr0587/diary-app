<?php

namespace Database\Factories;

use App\Enums\Mood;
use App\Models\Entry;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Entry>
 */
class EntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $moodValues = collect(Mood::cases())->pluck('value')->toArray();

        return [
            'user_id' => User::factory()->create(),
            'title' => fake()->sentence,
            'content' => fake()->text,
            'mood' => fake()->randomElement($moodValues)
        ];
    }
}
