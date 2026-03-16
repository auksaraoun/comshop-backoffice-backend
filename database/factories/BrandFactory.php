<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'ASUS', 'MSI', 'Gigabyte', 'EVGA', 'Corsair',
                'Samsung', 'Western Digital', 'Seagate', 'Kingston', 'G.Skill',
                'Noctua', 'be quiet!', 'Fractal Design', 'NZXT', 'Cooler Master',
            ]),
        ];
    }
}
