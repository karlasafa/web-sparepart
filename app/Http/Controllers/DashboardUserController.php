<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;

class DashboardUserController extends Controller
{
    // Aturan untuk melakukan validasi input pengguna
    protected $rules = [
        "name" => ["string", "required", "max:100"], // Nama harus berupa string dan tidak lebih dari 100 karakter
        "email" => ["email", "required", "max:50"], // Email harus valid dan tidak lebih dari 50 karakter
        "role" => ["string", "required"], // Peran harus berupa string
        "phone" => ["string", "required", "max:13"], // Nomor telepon harus berupa string dan tidak lebih dari 13 karakter
        "status" => ["integer"] // Status harus berupa bilangan bulat
    ];

    protected $password_rules = ["string", "required", "max:20"]; // Kata sandi harus berupa string dan tidak lebih dari 20 karakter
    protected $picture_rules = ["image", "file", "max:20000"]; // Gambar harus berupa file dengan ukuran maksimum 20MB

    /**
     * Menampilkan daftar pengguna dalam dashboard.
     *
     * @return \Illuminate\View\View Tampilan halaman daftar pengguna
     *
     * @description Fungsi ini melakukan proses:
     * - Mengambil seluruh data pengguna
     * - Mengambil daftar peran yang tersedia
     * - Menampilkan view index pengguna dengan data yang diperlukan
     *
     * @uses User::all() Mengambil semua data pengguna
     * @uses User::roles() Mendapatkan daftar peran yang tersedia
     */
    public function index()
    {
        // Render view index pengguna dengan data:
        return view("dashboard.users.index", [
            "users" => User::all(), // Seluruh data pengguna
            "roles" => User::roles() // Daftar peran yang tersedia
        ]);
    }

    /**
     * Menampilkan formulir untuk membuat pengguna baru.
     *
     * @return \Illuminate\View\View Tampilan formulir pembuatan pengguna
     *
     * @description Fungsi ini melakukan proses:
     * - Mengambil daftar peran yang tersedia
     * - Menampilkan view formulir pembuatan pengguna
     *
     * @uses User::roles() Mendapatkan daftar peran yang tersedia
     */
    public function create()
    {
        // Render view formulir pembuatan pengguna dengan daftar peran
        return view("dashboard.users.create", [
            "roles" => User::roles() // Daftar peran yang dapat dipilih
        ]);
    }

    /**
     * Menyimpan pengguna baru ke dalam sistem.
     *
     * @param \Illuminate\Http\Request $request Data input dari formulir
     *
     * @return \Illuminate\Http\RedirectResponse Respon pengalihan setelah proses penyimpanan
     *
     * @description Proses penyimpanan pengguna meliputi:
     * - Validasi input
     * - Penanganan unggah gambar profil
     * - Pembuatan entitas pengguna baru
     *
     * @throws \Exception Kesalahan yang mungkin terjadi selama proses penyimpanan
     */
    public function store(Request $request)
    {
        // Tambahkan aturan validasi untuk gambar profil jika file diunggah
        if ($request->file("picture")) {
            $this->rules["picture"] = $this->picture_rules;
        }

        // Tambahkan aturan validasi untuk kata sandi
        $this->rules["password"] = $this->password_rules;

        // Validasi semua input yang dikirim melalui formulir
        $validator = Validator::make($request->all(), $this->rules);

        // Kembalikan ke halaman formulir dengan pesan kesalahan jika validasi gagal
        if ($validator->fails()) {
            return redirect("dashboard/users/create")
                ->withErrors($validator->errors())
                ->withInput();
        }

        // Simpan data yang sudah tervalidasi
        $user = $validator->validated();

        // Proses unggah gambar profil jika ada
        if ($request->file("picture")) {
            $user["picture"] = $request->file("picture")->store("user-pictures", "public");
        }

        try {
            // Buat entitas pengguna baru
            User::create($user);

            // Kembalikan ke halaman formulir dengan pesan sukses
            return redirect("dashboard/users/create")
                ->with("alert-success", "Data created successfully!")
                ->with("prev-image", $user["picture"])
                ->withInput();
        } catch (\Throwable $th) {
            // Tangani kesalahan saat pembuatan pengguna
            return redirect("dashboard/users/create")
                ->with("alert-error", "Data failed to create!" . $th->getMessage())
                ->withInput();
        }
    }

    /**
     * Menampilkan formulir edit untuk pengguna tertentu.
     *
     * @param int $id Identifikasi unik pengguna
     *
     * @return \Illuminate\View\View Tampilan formulir edit pengguna
     *
     * @description Mengambil data pengguna untuk diedit
     */
    public function edit(int $id)
    {
        // Render view edit dengan data pengguna, peran, dan status
        return view("dashboard.users.edit", [
            "user" => User::find($id),
            "roles" => User::roles(),
            "statuses" => User::statuses()
        ]);
    }

    /**
     * Memperbarui data pengguna yang sudah ada.
     *
     * @param \Illuminate\Http\Request $request Data input dari formulir
     * @param int $id Identifikasi unik pengguna
     *
     * @return \Illuminate\Http\RedirectResponse Respon pengalihan setelah proses pembaruan
     *
     * @description Proses pembaruan pengguna meliputi:
     * - Validasi input
     * - Penanganan unggah gambar profil
     * - Pembaruan entitas pengguna
     *
     * @throws \Exception Kesalahan yang mungkin terjadi selama proses pembaruan
     */
    public function update(Request $request, int $id)
    {
        // Tambahkan aturan validasi untuk gambar profil jika file diunggah
        if ($request->file("picture")) {
            $this->rules["picture"] = $this->picture_rules;
        }

        // Tambahkan aturan validasi untuk kata sandi jika diubah
        if ($request->get("password")) {
            $this->rules["password"] = $this->password_rules;
        }

        // Validasi semua input yang dikirim melalui formulir
        $validator = Validator::make($request->all(), $this->rules);

        // Kembalikan ke halaman formulir dengan pesan kesalahan jika validasi gagal
        if ($validator->fails()) {
            return redirect("dashboard/users/$id/edit")
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
            return redirect("dashboard/users/$id/edit")
                ->with("alert-success", "Data updated successfully!")
                ->with("prev-image", $user["picture"])
                ->withInput();
        } catch (\Throwable $error) {
            // Tangani kesalahan saat pembaruan pengguna
            return redirect("dashboard/users/$id/edit")
                ->with("alert-error", "Data failed to update!" . $error->getMessage())
                ->withInput();
        }
    }

    /**
     * Menghapus pengguna tertentu dari sistem.
     *
     * @param int $id Identifikasi unik pengguna
     *
     * @return \Illuminate\Http\RedirectResponse Respon pengalihan setelah proses penghapusan
     *
     * @description Proses penghapusan pengguna meliputi:
     * - Pencarian entitas pengguna
     * - Penghapusan gambar profil
     * - Penghapusan entitas pengguna
     *
     * @throws \Exception Kesalahan yang mungkin terjadi selama proses penghapusan
     */
    public function destroy(int $id)
    {
        // Temukan entitas pengguna atau munculkan error 404
        $user = User::findOrFail($id);

        try {
            // Hapus entitas pengguna
            $user->delete();

            // Hapus gambar profil dari penyimpanan
            removeFromStorage($user->picture);

            // Kembalikan ke halaman daftar pengguna dengan pesan sukses
            return redirect("dashboard/users")
                ->with("alert-success", "Data deleted successfully!");
        } catch (\Throwable $error) {
            // Tangani kesalahan saat penghapusan pengguna
            return redirect("dashboard/users")
                ->with("alert-error", "Data failed to delete!" . $error->getMessage());
        }
    }

    /**
     * Menghasilkan laporan pengguna berdasarkan rentang tanggal tertentu.
     *
     * @param string $startDate Tanggal mulai untuk filter laporan (format: Y-m-d)
     * @param string $endDate Tanggal akhir untuk filter laporan (format: Y-m-d)
     *
     * @return \Illuminate\Http\Response Respon unduhan PDF laporan pengguna
     *
     * @throws \Exception Kesalahan yang mungkin terjadi selama pembuatan laporan
     *
     * @description Fungsi ini melakukan proses:
     * - Memfilter pengguna berdasarkan rentang tanggal yang diberikan
     * - Mengambil data pengguna dalam rentang waktu tertentu
     * - Menghasilkan laporan PDF atau mengarahkan ke halaman 404 jika tidak ada data
     *
     * @uses User::whereBetween() Menyaring pengguna berdasarkan rentang tanggal
     * @uses Pdf::loadView() Membuat dokumen PDF dari view yang ditentukan
     */
    public function report(string $startDate, string $endDate)
    {
        // Ambil data pengguna yang dibuat antara tanggal mulai dan akhir
        $users = User::whereBetween("created_at", [$startDate, $endDate])->get();

        // Periksa apakah tidak ada pengguna yang ditemukan dalam rentang tanggal
        if ($users->isEmpty()) {

            // Alihkan ke halaman 404 jika tidak ada data pengguna
            return redirect("dashboard/users/404");
        }

        // Siapkan data untuk pembuatan laporan PDF
        $data = [
            "users" => $users, // Daftar pengguna yang akan ditampilkan
            "startDate" => $startDate, // Tanggal mulai laporan
            "endDate" => $endDate // Tanggal akhir laporan
        ];

        // Buat dan unduh laporan dalam format PDF
        return Pdf::loadView("dashboard.users.contents.report", $data)->download();
    }

    /**
     * Menampilkan halaman laporan pengguna kosong.
     *
     * @return \Illuminate\View\View Tampilan halaman laporan dengan array pengguna kosong
     *
     * @description Fungsi ini digunakan untuk menampilkan halaman laporan
     * ketika tidak ada pengguna yang ditemukan dalam rentang tanggal tertentu.
     */
    public function notFound()
    {
        return view("dashboard.users.contents.report", ["users" => []]);
    }
}
