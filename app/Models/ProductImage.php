<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    /**
     * Daftar kolom yang tidak dapat diisi secara massal.
     *
     * Kolom 'id' dikecualikan dari mass assignment untuk
     * mencegah manipulasi ID gambar produk secara langsung.
     *
     * @var array
     */
    protected $guarded = ["id"];

    /**
     * Mendefinisikan relasi dengan model Product.
     *
     * Setiap gambar produk dimiliki oleh satu produk.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Relasi belongs to dengan Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Mengambil sumber gambar produk berdasarkan otorisasi pengguna.
     *
     * Metode ini melakukan:
     * - Mengambil semua gambar produk dengan relasi produk
     * - Untuk pengguna non-SuperAdmin, hanya mengambil gambar
     *   produk yang diterbitkan oleh pengguna yang sedang login
     *
     * @return \Illuminate\Database\Eloquent\Collection Kumpulan gambar produk
     *
     * @uses Auth::user() Mendapatkan pengguna yang sedang login
     * @uses static::with() Eager loading relasi produk
     */
    protected static function getSources()
    {
        // Inisialisasi query dengan relasi produk
        $sources = static::with("product");

        // Jika pengguna bukan SuperAdmin, filter gambar berdasarkan publisher
        if(Auth::user()->role !== "SuperAdmin") {
            $sources = static::with([
                // Gunakan query closure untuk memfilter produk berdasarkan publisher
                "product" => fn ($query) => $query->where("publisher", Auth::user()->id)
            ]);
        }

        // Kembalikan hasil query
        return $sources->get();
    }
}
