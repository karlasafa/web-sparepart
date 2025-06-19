<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Middleware untuk mengontrol akses berdasarkan peran pengguna.
     *
     * @param \Illuminate\Http\Request $request Objek permintaan HTTP
     * @param \Closure $next Fungsi penutup untuk melanjutkan permintaan
     *
     * @return \Symfony\Component\HttpFoundation\Response Respons HTTP
     *
     * @description Middleware ini melakukan:
     * - Pemeriksaan status autentikasi pengguna
     * - Pembatasan akses berdasarkan peran
     * - Mencegah akses tidak sah ke halaman tertentu
     *
     * @throws \Symfony\Component\HttpFoundation\HttpException Kesalahan otorisasi jika akses ditolak
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Periksa apakah pengguna sudah terautentikasi
        if (Auth::check()) {

            // Tolak akses untuk pengguna dengan peran Customer
            if (Auth::user()->role === "Customer") {
                return redirect("/");
            }

            // Batasi akses untuk pengguna non-SuperAdmin
            else if (Auth::user()->role !== "SuperAdmin") {

                // Tolak akses ke halaman manajemen pengguna
                if ($request->is("dashboard/users") || $request->is("dashboard/users/*")) {
                    abort(403, "Unauthorized access"); // Kembalikan error 403 Forbidden
                }
            }
        }

        // Lanjutkan permintaan jika lolos pemeriksaan otorisasi
        return $next($request);
    }
}
