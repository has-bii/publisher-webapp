<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content>
 */
class ContentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'cover' => 'dummy-cover.jpg',
            'price' => $this->faker->numerify('##'),
            'file' => null,
            'type_id' => 3,
            'genre_id' => $this->faker->numberBetween(6, 20),
            'author_id' => $this->faker->numberBetween(1, 54),
            'status_id' => 1,
            'publisher_id' => null,
            'published_date' => null,
            'created_at' => now(),
        ];
    }
}
