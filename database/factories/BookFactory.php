<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Book;
use App\Models\User;
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
            'lib_book_id' => fake()->unique()->numberBetween(1, 1000000),
            'author_fname' => fake()->name(),
            'author_lname' => fake()->name(),
            'title' => fake()->name(),
            'publisher_name' => fake()->name(),
            'publisher_place' => fake()->name(),
            'year' => fake()->year(),
            'loan' => fake()->boolean(),
            'lib_user_id' => User::factory(),
            'library_id' => Library::factory(),
            'featured' => fake()->boolean(),        
            //
        ];
    }
}
