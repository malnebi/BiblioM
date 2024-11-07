<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->name(),
            'last_name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'school_role' => fake()->randomElement(['student', 'teacher']),
            'class' => fake()->randomElement(['1st', '2nd', '3rd', '4th']),
            'head_class_teacher' => fake()->randomElement(['1st', '2nd', '3rd', '4th']),
            'school_subject' => fake()->randomElement(['English', 'Math', 'Science']),
        ];
    }
}
