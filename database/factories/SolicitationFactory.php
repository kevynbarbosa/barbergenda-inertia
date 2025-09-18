<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Solicitation>
 */
class SolicitationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'person_id' => \App\Models\Person::factory(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected', 'in_review']),
            'stage_id' => $this->faker->numberBetween(1, 5), // Assumes 5 stages from seeder
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }

}
