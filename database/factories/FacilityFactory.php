<?php

namespace Database\Factories;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Facility>
 */
class FacilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company() . ' Center',
            'description' => $this->faker->paragraph(),
            'location' => $this->faker->address(),
            'capacity' => $this->faker->numberBetween(10, 500),
            'price_per_hour' => $this->faker->randomFloat(2, 50, 2000),
            'is_available' => true,
        ];
    }
}
