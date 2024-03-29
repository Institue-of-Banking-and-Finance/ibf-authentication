<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'ulid' => Str::ulid(),
            'family_name' => fake()->name(),
            'given_name' => fake()->name(),
            'user_id' => fake()-> unique()->randomElement(User::all())['id'],
            'student_card' => fake()->numberBetween(1, 999999),
        ];
    }
}
