<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3), // Menghasilkan judul produk
            'description' => $this->faker->paragraph(), // Menghasilkan deskripsi produk
            'price' => $this->faker->numberBetween(1000, 100000000), // Menghasilkan harga antara 10 dan 1000
            'weight' => $this->faker->randomFloat(2, 0.1, 10), // Menghasilkan berat antara 0.1 dan 10
            'stock' => $this->faker->numberBetween(0, 100), // Menghasilkan stok antara 0 dan 100
            'image' => "product-images/example.jpg", // Menghasilkan URL gambar
            'status' => $this->faker->boolean(), // Menghasilkan status acak (true/false)
            'category_id' => $this->faker->numberBetween(1, 4), // Menggunakan factory untuk category
            'publisher' => $this->faker->numberBetween(1, 3), // Menggunakan factory untuk publisher
        ];
    }
}
