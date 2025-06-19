<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     *
     * Metode ini bertanggung jawab untuk menangani akses ke halaman login.
     * Jika pengguna sudah terautentikasi, akan diarahkan ke halaman dashboard.
     * Jika belum, akan menampilkan formulir login.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Halaman login atau redirect ke dashboard
     *
     * @throws \LogicException Jika terjadi kesalahan saat proses autentikasi
     */
    public function index()
    {
        // Periksa apakah pengguna sudah login
        // Jika ya, arahkan ke halaman dashboard untuk mencegah akses berulang
        if (Auth::check()) {
            return redirect("dashboard");
        }

        // Tampilkan halaman login untuk pengguna yang belum terautentikasi
        return view("auth.login");
    }

    /**
     * Memverifikasi kredensial pengguna untuk proses autentikasi.
     *
     * @param \Illuminate\Http\Request $request Objek request yang berisi data login
     *
     * @return \Illuminate\Http\RedirectResponse Pengalihan halaman setelah proses autentikasi
     *
     * @throws \Illuminate\Validation\ValidationException Kesalahan validasi input
     *
     * @description Fungsi ini melakukan proses:
     * - Validasi input email dan password
     * - Memeriksa kredensial pengguna
     * - Memvalidasi status akun
     * - Mengelola sesi login
     *
     * @uses Validator Melakukan validasi input
     * @uses Auth Melakukan proses autentikasi
     *
     * @todo Tambahkan mekanisme pembatasan percobaan login
     * @todo Implementasi logging percobaan login
     */
    public function verify(Request $request)
    {
        // @note Validasi input email dan password
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required"
        ]);

        // Tangani kegagalan validasi
        if($validator->fails()) {

            // @note Kembalikan ke halaman login dengan pesan kesalahan
            return redirect("login")->withErrors($validator)->withInput();
        }

        // Ambil data yang sudah tervalidasi
        $credentials = $validator->validated();

        // Proses autentikasi
        if (Auth::attempt($credentials)) {

            // @note Periksa status akun pengguna
            if (Auth::user()->status == 0) {

                // Logout jika akun belum diaktivasi
                Auth::logout();
                return redirect("login")->with("alert-error", "User Account not yet activated");
            }

            // @note Regenerasi sesi untuk keamanan
            $request->session()->regenerate();

            // @note Redirect ke dashboard setelah login berhasil
            return redirect("dashboard");
        }

        // @note Redirect dengan pesan error jika login gagal
        return redirect("login")->with("alert-error", "Login Failed, Credentials do not match");
    }

    /**
     * Melakukan proses logout pengguna.
     *
     * @return \Illuminate\Http\RedirectResponse Pengalihan ke halaman login
     *
     * @description Fungsi ini melakukan proses:
     * - Logout pengguna dari sistem
     * - Membatalkan sesi aktif
     * - Menghasilkan token sesi baru
     *
     * @uses Auth Melakukan proses logout
     *
     * @todo Tambahkan logging aktivitas logout
     * @todo Pertimbangkan pembersihan data sesi tambahan
     */
    public function logout()
    {
        // @note Logout pengguna
        Auth::logout();

        // @note Invalidasi dan regenerasi sesi untuk keamanan
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        // @note Redirect ke halaman login
        return redirect("login");
    }

    /**
     * Menampilkan halaman registrasi pengguna.
     *
     * @description Method ini bertanggung jawab untuk mengembalikan view halaman registrasi
     *              yang memungkinkan pengguna baru mendaftar ke dalam sistem.
     *
     * @return \Illuminate\View\View Halaman formulir registrasi.
     */
    public function register()
    {
        return view("auth.register");
    }

    /**
     * Memproses pendaftaran pengguna baru.
     *
     * @description Method ini melakukan validasi data pendaftaran, membuat akun pengguna baru,
     * dan mengarahkan pengguna ke halaman login atau menampilkan pesan kesalahan.
     *
     * @param \Illuminate\Http\Request $request Data formulir registrasi dari pengguna.
     * @return \Illuminate\Http\RedirectResponse Redirect ke halaman login atau kembali ke registrasi.
     *
     * @throws \Exception Jika terjadi kesalahan saat membuat akun pengguna.
     */
    public function registration(Request $request)
    {
        // Validasi input pendaftaran dengan aturan ketat
        // - Nama harus diisi, berupa string, maks 255 karakter
        // - Email harus unik, format email valid
        // - Password minimal 6 karakter, harus dikonfirmasi
        // - Nomor telepon antara 12-13 digit
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
            'phone' => 'required|string|min:12|max:13'
        ]);

        // Periksa hasil validasi
        // Jika validasi gagal, kembalikan ke halaman registrasi
        // dengan pesan kesalahan dan input yang diisi sebelumnya
        if ($validator->fails()) {
            return redirect("register")
                ->withErrors($validator) // Tampilkan pesan kesalahan validasi
                ->withInput(); // Kembalikan input sebelumnya
        }

        // Ambil data yang sudah divalidasi untuk keamanan
        $credentials = $validator->validated();

        // Proses pembuatan akun pengguna baru
        try {
            // Buat user baru di database dengan data yang valid
            User::create($credentials);

            // Redirect ke halaman login dengan pesan sukses
            // Memberikan konfirmasi bahwa registrasi berhasil
            return redirect("login")
                ->with("alert-success", "Registration Successful. Please log in.");

        } catch (\Exception $e) {
            // Tangani error yang mungkin terjadi saat proses pembuatan akun
            // Misalnya: duplikasi email, masalah koneksi database, dll
            return redirect("register")
                ->with("alert-error", "Registration Failed: " . $e->getMessage())
                ->withInput(); // Kembalikan input untuk mempermudah pengguna
        }
    }
}
