<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Electronics',
            'Fashion & Apparel',
            'Home & Garden',
            'Sports & Outdoors',
            'Books & Personal Care',
            'Toys & Games',
            'Automotive',
            'Health & Beauty',
            'Office Supplies',
            'Pet Supplies',
            'Food & Beverages',
        ]);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
            'meta_title' => $name . ' - Shop Online',
            'meta_description' => fake()->sentence(20),
        ];
    }
}
