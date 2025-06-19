<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DashboardProfileController extends Controller
{
    /**
     * Properti untuk menyimpan data pengguna yang sedang login.
     *
     * @var array $userData Menyimpan informasi pengguna yang terautentikasi
     */
    protected $userData;

    /**
     * Konstruktor untuk kontroler profil pengguna.
     *
     * Metode ini diinisialisasi setiap kali kontroler dipanggil.
     * Mengambil dan menyimpan data pengguna yang sedang login ke dalam properti $userData.
     *
     * @throws \Illuminate\Auth\AuthenticationException Jika tidak ada pengguna yang terautentikasi
     */
    public function __construct()
    {
        // Simpan data pengguna yang sedang login ke dalam array $userData
        // Menggunakan kunci 'user' untuk memudahkan akses di view
        $this->userData["user"] = Auth::user();
    }

    /**
     * Menampilkan halaman profil pengguna.
     *
     * @return \Illuminate\View\View Tampilan halaman profil pengguna
     *
     * @description Fungsi ini melakukan proses:
     * - Menampilkan view profil pengguna
     * - Menggunakan data pengguna yang telah disimpan sebelumnya
     */
    public function index()
    {
        // Render view profil dengan menggunakan data pengguna yang telah disimpan
        return view("dashboard.profile.index", $this->userData);
    }

    /**
     * Menampilkan formulir edit profil pengguna.
     *
     * @return \Illuminate\View\View Tampilan formulir edit profil pengguna
     *
     * @description Fungsi ini melakukan proses:
     * - Menampilkan view edit profil pengguna
     * - Menggunakan data pengguna yang telah disimpan sebelumnya
     */
    public function edit()
    {
        // Render view edit profil dengan menggunakan data pengguna yang telah disimpan
        return view("dashboard.profile.edit", $this->userData);
    }

    /**
     * Memperbarui data profil pengguna.
     *
     * @param Request $request Objek permintaan HTTP yang berisi data input
     * @param int $id Identifikasi unik pengguna
     *
     * @return \Illuminate\Http\RedirectResponse Redirect ke halaman edit profil
     *
     * @description Fungsi ini melakukan proses:
     * - Validasi input pengguna
     * - Unggah gambar profil (opsional)
     * - Memperbarui data pengguna
     * - Menangani kesalahan yang mungkin terjadi
     *
     * @uses Validator::make() Melakukan validasi input
     * @uses User::find() Mencari pengguna berdasarkan ID
     * @uses removeFromStorage() Menghapus file lama dari penyimpanan
     *
     * @throws \Throwable Kesalahan yang mungkin terjadi saat memperbarui data
     */
    public function update(Request $request, int $id)
    {
        // Aturan untuk melakukan validasi input pengguna
        $rules = [
            "name" => ["string", "required", "max:100"], // Nama harus berupa string dan tidak lebih dari 100 karakter
            "email" => ["email", "required", "max:50"], // Email harus valid dan tidak lebih dari 50 karakter
            "phone" => ["string", "required", "max:13"], // Nomor telepon harus berupa string dan tidak lebih dari 13 karakter
        ];

        // Tambahkan aturan validasi untuk gambar profil jika file diunggah
        if ($request->file("picture")) {

            // Gambar harus berupa file dengan ukuran maksimum 20MB
            $rules["picture"] = ["image", "file", "max:20000"];
        }

        // Tambahkan aturan validasi untuk kata sandi jika diubah
        if ($request->get("password")) {

            // Kata sandi harus berupa string dan tidak lebih dari 20 karakter
            $rules["password"] = ["string", "required", "max:20"];
        }

        // Validasi semua input yang dikirim melalui formulir
        $validator = Validator::make($request->all(), $rules);

        // Kembalikan ke halaman formulir dengan pesan kesalahan jika validasi gagal
        if ($validator->fails()) {
            return redirect("dashboard/profile/$id/edit")
                ->withErrors($validator->errors())
                ->withInput();
        }

        // Simpan data yang sudah tervalidasi
        $data = $validator->validated();

        // Temukan entitas pengguna yang akan diperbarui
        $user = User::find($id);

        // Proses unggah gambar profil jika ada
        if ($request->file("picture")) {

            // Hapus gambar profil lama
            removeFromStorage($user->picture);

            // Simpan gambar profil baru
            $data["picture"] = $request->file("picture")->store("user-pictures", "public");
        }

        try {
            // Perbarui entitas pengguna
            $user->update($data);

            // Kembalikan ke halaman formulir dengan pesan sukses
            return redirect("dashboard/profile/edit")
                ->with("alert-success", "Data updated successfully!")
                ->with("prev-image", $user["picture"])
                ->withInput();
        } catch (\Throwable $error) {
            // Tangani kesalahan saat pembaruan pengguna
            return redirect("dashboard/profile/edit")
                ->with("alert-error", "Data failed to update!" . $error->getMessage())
                ->withInput();
        }
    }
}
