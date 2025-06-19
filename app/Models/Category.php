<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    /**
     * Trait untuk mendukung pembuatan instance model secara dinamis.
     *
     * @uses HasFactory Memfasilitasi pembuatan model untuk testing dan seeding
     */
    use HasFactory;

    /**
     * Atribut yang dilindungi dari mass assignment.
     *
     * @var array<int, string>
     * @description Mencegah pengubahan langsung pada kolom ID
     *
     * @note Berbeda dengan $fillable, $guarded mendefinisikan
     * kolom yang tidak dapat diisi secara massal
     */
    protected $guarded = ["id"];

    /**
     * Definisi relasi satu-ke-banyak dengan model Produk.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     * @description Menentukan hubungan bahwa satu kategori
     * dapat memiliki banyak produk
     *
     * @example
     * // Mengambil semua produk dalam kategori tertentu
     * $category->products;
     *
     * // Menghitung jumlah produk dalam kategori
     * $productCount = $category->products()->count();
     */
    public function products()
    {
        // Hubungkan model Category dengan model Product
        // melalui foreign key category_id
        return $this->hasMany(Product::class);
    }
}
