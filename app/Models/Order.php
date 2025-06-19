<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
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
     * Mengubah kolom "products" menjadi tipe array.
     *
     * @description Properti ini mengaktifkan konversi tipe otomatis untuk atribut "products":
     * - Mengonversi data tersimpan dalam JSON menjadi array PHP saat pengambilan
     * - Mengonversi array PHP kembali ke JSON saat menyimpan ke database
     *
     * @var array
     * @example
     * // Menyimpan array ke dalam database
     * $model->products = ['item1', 'item2', 'item3'];
     *
     * // Mengambil dan menggunakan sebagai array
     * $products = $model->products; // Dapat langsung diakses sebagai array
     *
     * Keuntungan:
     * - Menyederhanakan penanganan tipe data kompleks
     * - Konversi JSON <-> Array otomatis
     * - Memastikan konsistensi tipe data
     */
    protected $casts = ["products" => "array"];

    /**
     * Mendapatkan daftar status pesanan yang valid dari definisi kolom database.
     *
     * @description Mengekstrak nilai-nilai enum yang terdefinisi pada kolom status
     *              di tabel orders, memungkinkan penggunaan status yang konsisten di seluruh aplikasi.
     *
     * @return array Kumpulan status pesanan yang valid (contoh: ['pending', 'success', 'failed']).
     * @throws \Exception Jika terjadi kesalahan saat mengambil status dari database.
     */
    protected function statuses()
    {
        return getEnum("orders", "status");
    }
}
