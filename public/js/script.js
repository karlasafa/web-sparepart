// Shortcut untuk mengambil elemen dari dokumen (single)
function element(selector) {
    return document.querySelector(selector);
}

// Shortcut untuk mengambil elemen dari dokumen (multiple)
function elements(selector) {
    return document.querySelectorAll(selector);
}

// Fungsi untuk membuat format rupiah
function rupiah(number) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR"
    }).format(number);
}


/**
 * Mengambil data dari URL yang diberikan menggunakan fetch API.
 *
 * @async
 * @param {string} url - URL endpoint yang akan diambil datanya.
 * @returns {Promise<Object>} - Mengembalikan Promise yang berisi data dalam format JSON.
 * @throws {Error} - Melemparkan error jika respons tidak ok atau jika respons bukan dalam format JSON.
 * @description Fungsi ini melakukan permintaan HTTP GET ke URL yang diberikan.
 * Jika respons berhasil dan konten adalah JSON, fungsi akan mengembalikan data yang diparse.
 * Jika tidak, fungsi akan melemparkan error yang sesuai.
 */
async function fetchData(url, options = {}) {

    // Melakukan permintaan fetch ke URL yang diberikan
    const response = await fetch(url, options);

    // Cek apakah respons berhasil
    if (!response.ok) {

        // Lempar error jika respons tidak ok
        throw new Error('Network response was not ok');
    }

    // Cek apakah Content-Type adalah application/json
    const contentType = response.headers.get('Content-Type');

    // Jika response adalah JSON, parse dan kembalikan
    if (contentType && contentType.includes('application/json')) {

        // Mengembalikan data yang diparse
        return await response.json();
    } else {

        // Lempar error jika respons bukan JSON
        throw new Error('Response is not JSON');
    }
}
