<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Genre>
 */
class GenreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'Fiction',
                'Mystery',
                'Science',
                'Fantasy',
                'Sci-fi',
                'Literary Fiction',
                'Horror',
                'Humor',
                'Politics'
            ]),
            'type_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}