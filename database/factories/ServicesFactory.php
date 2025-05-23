<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ServicesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = \App\Models\Services::class;
    public function definition(): array
    {
        return [
           'service_name' => $this->faker->name(),
            'duration_minute' => 20,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
