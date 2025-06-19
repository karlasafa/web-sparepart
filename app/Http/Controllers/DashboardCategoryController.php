<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DashboardCategoryController extends Controller
{
    // Aturan untuk melakukan validasi input
    protected $rules = ["title" => "required|string"]; // Harus diisi dan berupa string

    /**
     * Menampilkan daftar kategori.
     *
     * @return \Illuminate\View\View Halaman daftar kategori
     *
     * @description Fungsi ini melakukan proses:
     * - Mengambil seluruh kategori dari database
     * - Menampilkan data kategori pada halaman dashboard
     *
     * @uses Category::all() Mengambil semua data kategori
     *
     * @todo Tambahkan pagination untuk daftar kategori
     * @todo Implementasi filter dan pencarian kategori
     */
    public function index()
    {
        // @note Ambil semua kategori untuk ditampilkan
        return view("dashboard.categories.index", [
            "categories" => Category::all()
        ]);
    }

    /**
     * Menampilkan formulir pembuatan kategori baru.
     *
     * @return \Illuminate\View\View Halaman formulir pembuatan kategori
     *
     * @description Fungsi ini melakukan proses:
     * - Menampilkan formulir input kategori baru
     *
     * @todo Tambahkan validasi akses untuk membuat kategori
     * @todo Pertimbangkan mekanisme pembatasan pembuatan kategori
     */
    public function create()
    {
        // @note Tampilkan halaman formulir pembuatan kategori
        return view("dashboard.categories.create");
    }

    /**
     * Menyimpan kategori baru ke dalam database.
     *
     * @param \Illuminate\Http\Request $request Objek request yang berisi data kategori
     *
     * @return \Illuminate\Http\RedirectResponse Pengalihan halaman dengan pesan status
     *
     * @throws \Illuminate\Validation\ValidationException Kesalahan validasi input
     * @throws \Exception Kesalahan umum yang mungkin terjadi selama proses penyimpanan
     *
     * @description Fungsi ini melakukan proses:
     * - Validasi input kategori
     * - Menyimpan kategori baru ke dalam database
     * - Menangani keberhasilan atau kegagalan penyimpanan
     *
     * @uses Validator Melakukan validasi input kategori
     * @uses Category::create() Menyimpan kategori baru
     *
     * @todo Tambahkan mekanisme logging aktivitas
     * @todo Implementasi transaksi database untuk keamanan
     */
    public function store(Request $request)
    {
        // @note Validasi input kategori menggunakan aturan yang telah ditentukan
        $validator = Validator::make($request->all(), $this->rules);

        // Tangani kegagalan validasi
        if ($validator->fails()) {

            // @note Kembalikan ke halaman create dengan pesan kesalahan dan input sebelumnya
            return redirect("dashboard/categories/create")
                ->withErrors($validator->errors())
                ->withInput();
        }

        // Ambil data yang sudah tervalidasi
        $category = $validator->validated();

        try {
            // @note Buat kategori baru di database
            Category::create($category);

            // @note Redirect dengan pesan sukses dan input sebelumnya
            return redirect("dashboard/categories/create")
                ->with("alert-success", "Category successfully created!")
                ->withInput();
        } catch (\Throwable $error) {

            // @note Tangani kesalahan saat penyimpanan dan redirect dengan pesan error
            return redirect("dashboard/categories/create")
                ->with("alert-error", "Category failed to create!" . $error->getMessage())
                ->withInput();
        }
    }

    /**
     * Menampilkan formulir edit kategori.
     *
     * @param int $id Identifikasi unik kategori yang akan diedit
     *
     * @return \Illuminate\View\View Halaman formulir edit kategori
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika kategori tidak ditemukan
     *
     * @description Fungsi ini melakukan proses:
     * - Mencari kategori berdasarkan ID
     * - Menampilkan formulir edit kategori
     *
     * @uses Category::findOrFail() Mencari kategori atau lempar exception
     *
     * @todo Tambahkan validasi akses edit kategori
     * @todo Pertimbangkan pembatasan edit berdasarkan kondisi tertentu
     */
    public function edit(int $id)
    {
        // @note Cari kategori untuk diedit dan tampilkan formulir
        return view("dashboard.categories.edit", [
            "category" => Category::findOrFail($id)
        ]);
    }

    /**
     * Memperbarui kategori yang ada di database.
     *
     * @param \Illuminate\Http\Request $request Objek request yang berisi data kategori
     * @param int $id Identifikasi unik kategori yang akan diperbarui
     *
     * @return \Illuminate\Http\RedirectResponse Pengalihan halaman dengan pesan status
     *
     * @throws \Illuminate\Validation\ValidationException Kesalahan validasi input
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika kategori tidak ditemukan
     * @throws \Exception Kesalahan umum yang mungkin terjadi selama proses pembaruan
     *
     * @description Fungsi ini melakukan proses:
     * - Validasi input kategori
     * - Mencari kategori yang akan diupdate
     * - Memperbarui data kategori di database
     * - Menangani keberhasilan atau kegagalan update
     *
     * @uses Validator Melakukan validasi input kategori
     * @uses Category::findOrFail() Mencari kategori untuk diupdate
     * @uses Category::update() Memperbarui data kategori
     *
     * @todo Tambahkan mekanisme logging perubahan
     * @todo Implementasi validasi tambahan sebelum update
     */
    public function update(Request $request, int $id)
    {
        // @note Validasi input kategori menggunakan aturan yang telah ditentukan
        $validator = Validator::make($request->all(), $this->rules);

        // Tangani kegagalan validasi
        if ($validator->fails()) {

            // @note Kembalikan ke halaman edit dengan pesan kesalahan dan input sebelumnya
            return redirect("dashboard/categories/$id/edit")
                ->withErrors($validator->errors())
                ->withInput();
        }

        // Ambil data yang sudah tervalidasi
        $data = $validator->validated();

        try {
            // @note Cari dan update kategori di database
            Category::findOrFail($id)->update($data);

            // @note Redirect dengan pesan sukses dan input sebelumnya
            return redirect("dashboard/categories/$id/edit")
                ->with("alert-success", "Category successfully updated!")
                ->withInput();
        } catch (\Throwable $error) {
            // @note Tangani kesalahan saat update dan redirect dengan pesan error
            return redirect("dashboard/categories/$id/edit")
                ->with("alert-error", "Category failed to update!" . $error->getMessage())
                ->withInput();
        }
    }

    /**
     * Menghapus kategori dari database.
     *
     * @param int $id Identifikasi unik kategori yang akan dihapus
     *
     * @return \Illuminate\Http\RedirectResponse Pengalihan halaman dengan pesan status
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika kategori tidak ditemukan
     * @throws \Exception Kesalahan umum yang mungkin terjadi selama proses penghapusan
     *
     * @description Fungsi ini melakukan proses:
     * - Mencari kategori berdasarkan ID
     * - Memeriksa apakah kategori sedang digunakan oleh produk
     * - Menghapus kategori dari database jika tidak digunakan
     * - Menangani keberhasilan atau kegagalan penghapusan
     *
     * @uses Category::findOrFail() Mencari kategori untuk dihapus
     * @uses Category::delete() Menghapus kategori dari database
     *
     * @todo Tambahkan validasi kepemilikan atau izin penghapusan
     * @todo Pertimbangkan mekanisme penanganan kategori yang terikat
     */
    public function destroy(int $id)
    {
        try {
            // @note Cari kategori yang akan dihapus
            $category = Category::findOrFail($id);

            // @note Periksa apakah kategori digunakan oleh produk
            $relatedProducts = Product::where("category_id", $id)->get();

            // Jika ada produk yang menggunakan kategori, hentikan proses penghapusan
            if ($relatedProducts->isNotEmpty()) {

                // @note Kumpulkan ID produk
                $productIds = $relatedProducts->pluck("id")->implode(", ");

                // @note Redirect dengan pesan kesalahan dan sertakan ID produk
                return redirect("dashboard/categories")
                    ->with("alert-error", "Kategori tidak dapat dihapus. Produk terkait: [{$productIds}]");
            }

            // @note Hapus kategori jika tidak ada produk terkait
            $category->delete();

            // @note Redirect dengan pesan sukses
            return redirect("dashboard/categories")
                ->with("alert-success", "Category successfully deleted!");

        } catch (\Throwable $error) {
            // @note Tangani kesalahan saat penghapusan dan redirect dengan pesan error
            return redirect("dashboard/categories")
                ->with("alert-error", "Category failed to delete!" . $error->getMessage());
        }
    }
}
