<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->unique()->name();
        $slug = Str::slug($title);

        $categoryId = Category::inRandomOrder()->value('id');
        if (empty($categoryId)) {
            $categoryId = Category::factory()->create()->id;
        }

        $subCategoryId = SubCategory::inRandomOrder()->value('id') ?? null;

        $brandId = Brand::inRandomOrder()->value('id') ?? null;

        return [
            'title'          => $title,
            'slug'           => $slug,
            'category_id'    => $categoryId,
            'sub_category_id'=> $subCategoryId,
            'brand_id'       => $brandId,
            'price'          => rand(10, 1000),
            'sku'            => rand(1000, 1000000),
            'track_qty'      => rand(0, 1) ? 'Yes' : 'No',
            'qty'            => rand(10, 100),
            'is_featured'    => rand(0, 1) ? 'Yes' : 'No',
            'status'         => 1,
        ];
    }
}
