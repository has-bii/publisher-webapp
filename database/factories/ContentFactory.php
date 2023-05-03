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
            'cover' => $this->faker->imageUrl(480, 640, 'animals', true),
            'price' => $this->faker->numerify('##'),
            'file' => null,
            'type_id' => $this->faker->randomDigitNotNull(),
            'author_id' => $this->faker->numberBetween(1, 20),
            'status_id' => $this->faker->numberBetween(1, 4),
            'publisher_id' => $this->faker->numberBetween(1, 10),
            'published_date' => null,
            'upload_date' => now(),
        ];
    }
}
