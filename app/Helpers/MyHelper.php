<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Mengubah angka menjadi format mata uang Rupiah.
 *
 * @param int $number Angka yang akan diubah menjadi format Rupiah
 * @return string Representasi angka dalam format mata uang Rupiah
 *
 * @description Fungsi ini mengonversi angka menjadi format mata uang Rupiah
 * dengan menambahkan prefix 'Rp' dan memformat angka dengan separator ribuan.
 *
 * @example
 * rupiah(1000000) // Mengembalikan "Rp 1.000.000,00"
 */
function rupiah(int $number)
{
    return 'Rp ' . number_format($number, 2, ',', '.');
}

/**
 * Memeriksa dan menentukan status aktif untuk tautan navigasi.
 *
 * @param string $route Rute yang akan diperiksa
 * @param bool $nesting Apakah akan memeriksa rute bersarang (default: true)
 * @return string Mengembalikan 'active' jika rute cocok, string kosong jika tidak
 *
 * @description Fungsi ini memeriksa apakah rute saat ini cocok dengan rute yang diberikan.
 * Dengan $nesting=true, akan mencocokkan rute induk dan rute turunannya.
 *
 * @uses Request::is() Memeriksa kecocokan rute
 *
 * @example
 * setActiveLink('dashboard') // Mengembalikan 'active' jika di halaman dashboard
 * setActiveLink('dashboard', false) // Hanya aktif untuk rute dashboard tepat
 */
function setActiveLink(string $route, bool $nesting = true) {
    if($nesting) {
        // Cocokkan rute tepat atau rute bersarang
        return Request::is($route) || Request::is($route . "/*") ? 'active' : '';
    } else {
        // Cocokkan rute tepat
        return Request::is($route) ? 'active' : '';
    }
}

/**
 * Menghapus file dari penyimpanan publik.
 *
 * @param string|null $fileName Nama file yang akan dihapus
 *
 * @description Fungsi ini menghapus file dari penyimpanan publik jika file ada.
 * Berguna untuk membersihkan file yang sudah tidak diperlukan.
 *
 * @uses Storage::disk() Mengakses disk penyimpanan
 * @uses Storage::exists() Memeriksa keberadaan file
 * @uses Storage::delete() Menghapus file
 *
 * @example
 * removeFromStorage('user-pictures/profile.jpg') // Menghapus file jika ada
 */
function removeFromStorage($fileName)
{
    // Periksa apakah nama file tidak kosong
    if ($fileName) {

        // Periksa apakah file ada di disk publik
        if (Storage::disk('public')->exists($fileName)) {

            // Hapus file dari disk
            Storage::disk('public')->delete($fileName);
        }
    }
}

/**
 * Memformat tanggal ke dalam format yang diinginkan.
 *
 * @param string $dateString Tanggal dalam format yang dapat diubah oleh strtotime()
 * @param string $format Format tanggal keluaran (default: "d F Y")
 * @return string Tanggal yang diformat
 *
 * @description Fungsi ini mengubah string tanggal menjadi format yang diinginkan
 * menggunakan fungsi date() dan strtotime().
 *
 * @uses strtotime() Mengonversi string tanggal menjadi timestamp
 * @uses date() Memformat timestamp menjadi string tanggal
 *
 * @example
 * dateFormatter('2023-05-20') // Mengembalikan "20 Mei 2023"
 * dateFormatter('2023-05-20', 'Y-m-d') // Mengembalikan "2023-05-20"
 */
function dateFormatter($dateString, $format = "d F Y")
{
    return date($format, strtotime($dateString));
}

/**
 * Mendapatkan daftar metode pembayaran yang tersedia.
 *
 * @return array Daftar metode pembayaran dalam bentuk array.
 */
function paymentMethods()
{
    // Mengembalikan daftar metode pembayaran yang didukung
    return ["Cash", "Debit", "QRIS"];
}

/**
 * Mengubah daftar produk menjadi format JSON dengan informasi id dan kuantitas.
 *
 * @param array|string $products Daftar produk dalam bentuk array atau JSON string.
 * @return string JSON yang dihasilkan dengan daftar produk yang sudah diproses.
 *
 * @throws InvalidArgumentException Jika input bukan array atau JSON yang valid.
 */
function jsonProducts($products)
{
    // Jika input adalah string JSON, konversi menjadi array asosiatif
    if (is_string($products)) {
        // Dekode JSON string menjadi array, dengan parameter true untuk menghasilkan array asosiatif
        $products = json_decode($products, true);
    }

    // Validasi input, pastikan $products adalah array sebelum diproses
    if (!is_array($products)) {

        // Lempar exception jika input tidak valid
        throw new InvalidArgumentException('Expected an array of products.');
    }

    // Transform produk menjadi array baru dengan hanya field id dan quantity
    // Gunakan json_encode dengan opsi JSON_PRETTY_PRINT untuk format JSON yang rapi
    $jsonOutput = json_encode(array_map(function($product) {

        // Ekstrak dan kembalikan hanya id dan quantity dari setiap produk
        return [
            'id' => $product['id'],
            'kuantitas' => $product['quantity'],
        ];
    }, $products), JSON_PRETTY_PRINT);

    // Kembalikan string JSON yang dihasilkan
    return $jsonOutput;
}

/**
 * Mengekstrak daftar nilai ENUM dari kolom database menggunakan query metadata.
 *
 * Metode ini melakukan query ke tabel INFORMATION_SCHEMA untuk mendapatkan definisi tipe kolom,
 * kemudian menggunakan regex untuk mengekstrak nilai-nilai ENUM yang terdefinisi.
 *
 * @param string $tableName Nama tabel database yang akan diperiksa.
 * @param string $columnName Nama kolom spesifik yang memiliki tipe data ENUM.
 * @return array Kumpulan nilai-nilai ENUM yang valid untuk kolom tersebut.
 * @throws \Exception Jika terjadi kesalahan dalam proses ekstraksi.
 */
function getEnum(string $tableName, string $columnName)
{
    // Query metadata kolom dari INFORMATION_SCHEMA untuk mendapatkan definisi tipe
    $columnType = DB::table('INFORMATION_SCHEMA.COLUMNS')
        ->select('COLUMN_TYPE')
        ->where('TABLE_NAME', $tableName)
        ->where('COLUMN_NAME', $columnName)
        ->value('COLUMN_TYPE');

    // Gunakan regex untuk mencocokkan dan mengekstrak nilai-nilai ENUM
    // Regex ini menangkap isi dalam tanda kurung ENUM, tidak peduli dengan spasi atau kutipan
    if (preg_match("/ENUM\((.*)\)/i", $columnType, $matches)) {

        // Pecah nilai ENUM, bersihkan spasi dan tanda kutip, kemudian kembalikan sebagai array
        return collect(explode(',', $matches[1]))
            ->map(fn($value) => trim($value, " '"))
            ->all();
    }

    // Kembalikan array kosong jika tidak ada ENUM yang ditemukan
    return [];
}
