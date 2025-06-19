<?php

/**
 * Konfigurasi Aplikasi Laravel
 *
 * File ini bertanggung jawab untuk inisialisasi dan konfigurasi
 * dasar aplikasi Laravel, termasuk routing, middleware, dan penanganan eksepsi.
 *
 * @package Laravel
 * @subpackage Bootstrap
 */

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;

/**
 * Konfigurasi dan Inisialisasi Aplikasi Laravel
 *
 * Method ini melakukan konfigurasi utama aplikasi dengan beberapa langkah:
 * 1. Konfigurasi basis path aplikasi
 * 2. Pengaturan routing
 * 3. Konfigurasi middleware
 * 4. Konfigurasi penanganan eksepsi
 *
 * @return \Illuminate\Foundation\Application Instance aplikasi Laravel yang terkonfigurasi
 */
return Application::configure(
    // Tentukan basis path aplikasi
    // dirname(__DIR__) menghasilkan direktori induk dari direktori saat ini
    basePath: dirname(__DIR__)
)
    // Konfigurasi routing aplikasi
    ->withRouting(
        // Tentukan path file route web
        web: __DIR__ . '/../routes/web.php',

        // Tentukan path file route console/command
        commands: __DIR__ . '/../routes/console.php',

        // Endpoint health check bawaan Laravel
        // Biasa digunakan untuk pengecekan status aplikasi (misal: oleh load balancer)
        health: '/up',
    )
    // Konfigurasi middleware
    ->withMiddleware(function (Middleware $middleware) {
        // Daftarkan alias middleware kustom
        // Memudahkan penggunaan middleware dengan nama pendek
        $middleware->alias([
            // Middleware untuk mencegah caching
            // Berguna untuk halaman yang selalu memerlukan data fresh
            'no-cache' => App\Http\Middleware\NoCache::class,

            // Middleware untuk pengecekan peran pengguna
            // Membatasi akses berdasarkan role/peran
            'check-role' => App\Http\Middleware\CheckUserRole::class
        ]);
    })
    // Konfigurasi penanganan eksepsi
    ->withExceptions(function (Exceptions $exceptions) {
        // Ruang untuk konfigurasi kustom penanganan eksepsi
        // Contoh: laporan, rendering, atau transformasi eksepsi
        // Saat ini masih kosong, dapat diisi sesuai kebutuhan
    })
    // Buat dan kembalikan instance aplikasi
    ->create();
