<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NoCache
{
    /**
     * Middleware untuk mencegah penyimpanan cache pada respons.
     *
     * @param \Illuminate\Http\Request $request Objek permintaan HTTP
     * @param \Closure $next Fungsi penutup untuk melanjutkan permintaan
     *
     * @return \Symfony\Component\HttpFoundation\Response Respons HTTP tanpa cache
     *
     * @description Middleware ini bertujuan untuk:
     * - Mencegah penyimpanan respons di cache browser
     * - Memastikan konten selalu diambil dari server
     * - Melindungi informasi sensitif dari penyimpanan sementara
     *
     * @note Berguna untuk halaman yang memerlukan data terbaru setiap kali diakses
     * seperti halaman dashboard, profil pengguna, atau konten dinamis
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Lanjutkan permintaan dan dapatkan respons
        $response = $next($request);

        // Tetapkan header cache-control untuk mencegah penyimpanan cache
        $response->headers->add([
            // Instruksi untuk tidak menyimpan cache dalam berbagai kondisi
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',

            // Header tambahan untuk kompatibilitas dengan browser lama
            'Pragma' => 'no-cache',

            // Tetapkan tanggal kedaluwarsa di masa lalu
            'Expires' => 'Fri, 01 Jan 1990 00:00:00 GMT',
        ]);

        // Kembalikan respons dengan header anti-cache
        return $response;
    }
}
