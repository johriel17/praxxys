<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Product::class;

    public function definition(): array
    {
        $categories = ['Cat1', 'Cat2', 'Cat3'];

        return [
            'name' => $this->faker->word,
            'category' => $this->faker->randomElement($categories),
            'description' => $this->faker->paragraph,
            'date_and_time' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}