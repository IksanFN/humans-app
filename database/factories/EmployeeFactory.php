<?php

namespace Database\Factories;

use App\Enums\EmployeeStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'department_id' => rand(1, 5),
            'position_id' => rand(1, 5),
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'joined' => $this->faker->date(),
            'status' => collect(EmployeeStatus::cases())->random(),
        ];
    }
}
