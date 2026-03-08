<?php

namespace Database\Factories;

use App\Models\AdminUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductType>
 */
class ProductTypeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'     => fake()->randomElement([
                'CPU', 'GPU', 'Motherboard', 'RAM', 'SSD', 'HDD',
                'Power Supply', 'CPU Cooler', 'PC Case', 'Monitor',
                'Keyboard', 'Mouse', 'Headset', 'Webcam',
            ]),
            'admin_id' => AdminUser::factory(),
        ];
    }
}
