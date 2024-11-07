<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Book;
use App\Models\Client;
use App\Models\Library;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_fname' => fake()->name(),
            'author_lname' => fake()->name(),
            'title' => fake()->name(),
            'publisher_name' => fake()->name(),
            'publisher_place' => fake()->name(),
            'year' => fake()->year(),
            'loan' => fake()->boolean(),
            'client_id' => Client::factory(),
            'library_id' => Library::factory(),
            'featured' => fake()->boolean(),        
            //
        ];
    }
}
