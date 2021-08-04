<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'image' => $this->faker->image(),
            'price' => rand(200000, 500000),
            'detail' => $this->faker->name(),
            'cate_id' => rand(6, 20),
            'code_sale' => $this->faker->name(),
            'amount' => rand(10, 30),
            'status' => rand(0, 1),
            'album' => $this->faker->image()
        ];
    }
}