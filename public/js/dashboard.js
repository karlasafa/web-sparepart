/**
 * Menginisialisasi event listener saat DOM sepenuhnya dimuat.
 *
 * @description Fungsi ini akan menangani beberapa inisialisasi setelah halaman dimuat,
 * termasuk menampilkan pesan flash, mengatur pengaturan tabel data, dan menambahkan
 * kelas CSS untuk elemen tertentu.
 */
document.addEventListener("DOMContentLoaded", function () {

    // Mendapatkan elemen dengan ID 'index-page'
    const indexPage = element("#index-page");

    // Cek jika elemen dengan ID 'flash-message' ada
    if (element("#flash-message")) {

        // Set timeout untuk mengalihkan pengguna setelah 1 detik
        setTimeout(function () {

            // Jika elemen indexPage ada, alihkan ke URL yang diberikan
            if(indexPage) {
                window.location.href = indexPage.value;
            } else {

                // Jika tidak ada, muat ulang halaman
                window.location.reload();
            }
        }, 1000); // Durasi timeout dalam milidetik
    }

    // Cek jika elemen dengan class 'table' ada
    if(element(".table")) {

        // Inisialisasi tabel data dengan konfigurasi tertentu
        const dataTable = new DataTable('.table', {
            perPage: 10, // jumlah item per halaman
            perPageSelect: [10, 20], // pilihan jumlah item per halaman
            searchable: true, // mengaktifkan pencarian
            sortable: true, // mengaktifkan pengurutan
        });
    }

    // Menambahkan kelas CSS
    element(".dataTable-top").classList.add("px-4", "py-0");
    element(".dataTable-top").querySelector("label").classList.add("m-0");
    element(".dataTable-selector").classList.add("form-select");
    element(".dataTable-input").classList.add("form-control");
    element(".dataTable-container").classList.add("px-3");
    element(".dataTable-bottom").classList.add("px-4");
    element(".dataTable-bottom").querySelector(".dataTable-info").classList.add("mb-0");
});


/**
 * Menampilkan pratinjau gambar yang dipilih oleh pengguna.
 *
 * @param {EventTarget} eventTarget - Elemen input file yang memicu fungsi ini.
 * @description Fungsi ini mengambil file gambar dari elemen input, membaca file menggunakan FileReader,
 * dan menampilkan gambar pratinjau di elemen gambar dengan ID 'preview'. Jika tidak ada file yang dipilih,
 * gambar pratinjau akan disembunyikan.
 */
function preview(eventTarget) {

    // Mengambil file pertama dari input file
    const file = eventTarget.files[0];

    // Mendapatkan elemen gambar untuk pratinjau
    const preview = document.getElementById('preview');

    if (file) {

        // Membuat instance FileReader untuk membaca file
        const reader = new FileReader();

        // Ketika file selesai dibaca, atur src gambar pratinjau
        reader.onload = function (e) {

            // Mengatur src gambar dengan hasil pembacaan file
            preview.src = e.target.result;

            // Menambahkan class 'active' untuk menampilkan gambar
            preview.classList.add("active");

            // Menyembunyikan elemen lain yang mungkin menunjukkan gambar sebelumnya
            element("#prev-image").classList.add("d-none");
        }

        // Membaca file sebagai URL data
        reader.readAsDataURL(file);
    } else {

        // Mengatur src gambar pratinjau menjadi '#' jika tidak ada file
        preview.src = '#';

        // Menghapus class 'active' untuk menyembunyikan gambar jika tidak ada file
        preview.classList.remove("active");
    }
}
