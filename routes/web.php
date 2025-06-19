<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardUserController;
use App\Http\Controllers\DashboardOrderController;
use App\Http\Controllers\DashboardProductController;
use App\Http\Controllers\DashboardProfileController;
use App\Http\Controllers\DashboardCategoryController;
use App\Http\Controllers\DashboardProductImageController;

/**
 * Definisi Rute Aplikasi
 *
 * @description Konfigurasi rute untuk berbagai fungsionalitas aplikasi
 * - Rute dashboard dengan middleware autentikasi dan otorisasi
 * - Rute autentikasi untuk login, logout, dan verifikasi
 *
 * @middleware-group auth Memastikan pengguna telah terautentikasi
 * @middleware-group check-role Memeriksa otorisasi pengguna
 * @middleware-group no-cache Mencegah cache halaman
 *
 * @route-group Dashboard Berisi rute-rute untuk area dashboard
 * @route-group Authentication Berisi rute-rute untuk proses autentikasi
 */

/**
 * Rute Dashboard dengan Middleware Autentikasi
 *
 * @description Kumpulan rute yang memerlukan autentikasi dan otorisasi
 * - Berisi rute untuk halaman dashboard
 * - Rute manajemen pengguna
 * - Rute manajemen pesanan
 * - Rute manajemen produk
 * - Rute profil pengguna
 * - Rute sumber daya (resource)
 *
 * @uses DashboardUserController Kontroler untuk manajemen pengguna
 * @uses DashboardProductController Kontroler untuk manajemen produk
 * @uses DashboardProfileController Kontroler untuk profil pengguna
 */

Route::middleware(["auth", "check-role", "no-cache"])->group(function () {
    Route::prefix("dashboard")->group(function () {
        // Halaman beranda dashboard
        Route::get("/", fn () => view("dashboard.home"));

        // Rute khusus untuk pengguna
        Route::controller(DashboardUserController::class)->group(function () {
            Route::get("users/report/{startDate}/{endDate}", "report");
            Route::get("users/404", "notFound");
        });

        // Rute khusus untuk produk
        Route::controller(DashboardProductController::class)->group(function () {
            Route::get("products/report/{startDate}/{endDate}", "report");
            Route::get("products/404", "notFound");
        });

        // Rute khusus untuk pesanan
        Route::controller(DashboardOrderController::class)->group(function () {
            Route::get("orders/report/{startDate}/{endDate}", "report");
            Route::get("orders/report/print/{startDate}/{endDate}", "print");
            Route::get("orders/404", "notFound");
        });

        // Rute profil pengguna
        Route::controller(DashboardProfileController::class)->group(function () {
            Route::get("profile", "index");
            Route::get("profile/edit", "edit");
            Route::put("profile/{id}", "update");
        });

        // Rute sumber daya untuk manajemen data
        Route::resource("users", DashboardUserController::class)->except(["show"]);
        Route::resource("categories", DashboardCategoryController::class)->except(["show"]);
        Route::resource("products", DashboardProductController::class);
        Route::resource("orders", DashboardOrderController::class)->except(["create", "store"]);
        Route::resource("product-images", DashboardProductImageController::class)->except(["show"]);
    });
});

/**
 * Rute Autentikasi
 *
 * @description Kumpulan rute untuk proses autentikasi
 * - Halaman login
 * - Verifikasi kredensial
 * - Logout
 *
 * @uses AuthController Kontroler untuk proses autentikasi
 */
Route::controller(AuthController::class)->group(function () {
    // Halaman login
    Route::get("login", "index")->name("login");

    // Proses autentikasi
    Route::post("authenticate", "verify");

    // Proses logout
    Route::post("logout", "logout");

    // Halaman registrasi
    Route::get("register", "register")->name("register");

    Route::post("registration", "registration");
});

// Rute khusus untuk halaman utama
Route::middleware(["no-cache"])->group(function () {
    Route::controller(CustomerController::class)->group(function () {
        Route::get("/", "index");
        Route::get("products", "products");
        Route::get("product/{id}", "product");
        Route::get("category/{id}", "category");
        Route::get("cart", "cart");
        Route::get("cart/clear", "clearCart");
        Route::get("receipt/{orderId}", "receipt");
        Route::post("cart/add", "addToCart");
        Route::post("cart/subtract", "decreaseQuantity");
        Route::post("cart/remove", "removeFromCart");
        Route::post("checkout", "checkout")->middleware(["auth"]);
    });
});

// Route::get("/", [CustomerController::class, "topProducts"]);

require __DIR__ . "/backend.php";
?>
