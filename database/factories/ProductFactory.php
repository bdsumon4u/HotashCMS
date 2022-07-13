<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
    public function definition()
    {
        $regularPrice = mt_rand(100, 1000);
        $discountAmount = mt_rand(10, 15);
        $discountType = ['flat', 'percent'][mt_rand(0, 1)];
        switch ($discountType) {
            case 'flat': {
                $salePrice = $regularPrice - $discountAmount;
                break;
            }
            case 'percent': {
                $salePrice = $regularPrice * (1 - $discountAmount / 100);
                break;
            }
            default: $salePrice = $regularPrice;
        }
        return [
            'sku' => $this->faker->unique()->word(),
            'barcode' => $this->faker->unique()->word(),
            'regular_price' => $regularPrice,
            'discount_amount' => $discountAmount,
            'discount_type' => $discountType,
            'sale_price' => $salePrice,
            'scheduled' => false,
            'sale_start_date' => null,
            'sale_end_date' => null,
            'name' => $name = $this->faker->unique()->sentence(),
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraphs(3, true),
            'type' => 'standard',
            'brand_id' => mt_rand(1, 3),
        ];
    }
}
