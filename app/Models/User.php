<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /**
     * Trait untuk mendukung fitur-fitur autentikasi dan API.
     *
     * @uses HasApiTokens Mendukung autentikasi berbasis token
     * @uses HasFactory Mendukung pembuatan instance model untuk testing
     * @uses Notifiable Mendukung pengiriman notifikasi
     */
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = ["id"];

    /**
     * Atribut yang disembunyikan saat serialisasi.
     *
     * @var array<int, string>
     * @description Melindungi informasi sensitif seperti kata sandi
     */
    protected $hidden = [
        "password",
        "remember_token",
    ];

    /**
     * Konfigurasi tipe data untuk atribut tertentu.
     *
     * @return array<string, string>
     * @description Mengatur konversi tipe data dan enkripsi
     */
    protected function casts(): array
    {
        return [
            // Konversi kolom email_verified_at menjadi objek datetime
            "email_verified_at" => "datetime",

            // Enkripsi otomatis untuk kolom password
            "password" => "hashed",
        ];
    }

    /**
     * Mengambil daftar peran unik pengguna.
     *
     * @return \Illuminate\Support\Collection
     * @description Mengembalikan kumpulan peran yang berbeda dari seluruh pengguna
     */
    protected function roles()
    {
        // Ambil nilai peran unik dari seluruh pengguna
        return $this->distinct()->pluck("role");
    }

    /**
     * Mengambil daftar status unik pengguna.
     *
     * @return \Illuminate\Support\Collection
     * @description Mengembalikan kumpulan status yang berbeda dari seluruh pengguna
     */
    protected function statuses()
    {
        // Ambil nilai status unik dari seluruh pengguna
        return $this->distinct()->pluck("status");
    }

    /**
     * Definisi relasi satu-ke-banyak dengan model Produk.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @description Satu pengguna dapat memiliki banyak produk
     */
    public function products()
    {
        // Hubungkan model User dengan model Product
        return $this->hasMany(Product::class);
    }
}
