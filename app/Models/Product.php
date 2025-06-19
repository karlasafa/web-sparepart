<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    /**
     * Trait untuk mendukung pembuatan instance model secara dinamis.
     *
     * @uses HasFactory Memfasilitasi pembuatan model untuk testing dan seeding
     */
    use HasFactory;

    /**
     * Daftar kolom yang tidak dapat diisi secara massal.
     *
     * Kolom "id" dikecualikan dari mass assignment untuk mencegah
     * manipulasi ID produk secara langsung.
     *
     * @var array
     */
    protected $guarded = ["id"];

    /**
     * Mendefinisikan relasi dengan model Category.
     *
     * Setiap produk dimiliki oleh satu kategori.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Relasi belongs to dengan Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Mendefinisikan relasi dengan model User sebagai publisher.
     *
     * Setiap produk dimiliki oleh satu pengguna (publisher).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Relasi belongs to dengan User
     */
    public function publisher()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendefinisikan relasi dengan model ProductImage.
     *
     * Satu produk dapat memiliki banyak gambar.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany Relasi has many dengan ProductImage
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Mengembalikan koleksi status produk yang tersedia.
     *
     * Status yang dikembalikan berupa objek dengan dua properti:
     * - value: Nilai status (1 untuk "Published", 0 untuk "Blocked")
     * - text: Deskripsi status
     *
     * @return \Illuminate\Support\Collection Koleksi status produk
     */
    protected function statuses()
    {
        $statuses = collect([
            [
                "value" => 1,
                "text" => "Published"
            ],
            [
                "value" => 0,
                "text" => "Blocked"
            ]
        ]);

        // Mengubah setiap status menjadi objek
        return $statuses->map(fn ($status) => (object) $status);
    }

    /**
     * Menginisialisasi model dengan event booting.
     *
     * Event ini akan dipanggil saat model dihapus.
     * Ketika produk dihapus, semua gambar yang terkait dengan produk
     * akan dihapus dari penyimpanan dan basis data.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Menangani event deleting untuk menghapus gambar terkait produk
        static::deleting(function ($product) {
            // Menghapus setiap gambar dari penyimpanan jika ada
            foreach($product->images as $image) {
                if(Storage::disk('public')->exists($image->source)) {
                    Storage::disk('public')->delete($image->source);
                }
            }

            // Menghapus entri gambar dari basis data
            $product->images()->delete();
        });
    }

    /**
     * Mengambil seluruh produk dengan filter berdasarkan role pengguna.
     *
     * @return \Illuminate\Database\Eloquent\Collection Kumpulan produk dengan relasi kategori dan publisher
     *
     * @description Fungsi ini melakukan proses:
     * - Mengambil seluruh produk beserta relasi kategori dan publisher
     * - Memfilter produk berdasarkan publisher jika pengguna bukan SuperAdmin
     *
     * @uses static::with() Mengambil data produk dengan relasi terkait
     * @uses Auth::user() Mendapatkan informasi pengguna yang sedang login
     *
     * @note Metode statis yang dapat dipanggil langsung pada model
     * @note Mendukung akses berbeda untuk SuperAdmin dan publisher biasa
     *
     * @example
     * // Mengambil semua produk (SuperAdmin)
     * $allProducts = Product::getAll();
     *
     * @example
     * // Mengambil produk milik publisher yang sedang login
     * $publisherProducts = Product::getAll();
     */
    protected static function getAll()
    {
        // Inisialisasi query produk dengan relasi kategori dan publisher
        $products = static::with(["category", "publisher"]);

        // Filter produk berdasarkan publisher jika bukan SuperAdmin
        if(Auth::user()->role !== "SuperAdmin") {
            $products = $products->where("publisher", "=", Auth::user()->id);
        }

        // Kembalikan seluruh produk sesuai filter
        return $products->get();
    }

    protected static function publishedData()
    {
        return static::where("status", 1);
    }
}
