<?php

use App\Http\Controllers\WebhookController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use Illuminate\Support\Facades\Route;

// ============================================================
// PUBLIC ROUTES
// ============================================================
Route::get('/', function () {
    $packages = \App\Models\Package::active()->with('reviews')->get();
    $reviews  = \App\Models\Review::where('is_approved', true)->with('user','package')->latest()->take(3)->get();
    $averageRating = \App\Models\Review::where('is_approved', true)->avg('rating') ?: 0;
    $reviewCount = \App\Models\Review::where('is_approved', true)->count();

    return view('customer.home.index', compact('packages', 'reviews', 'averageRating', 'reviewCount'));
})->name('home');

Route::get('/tentang', function () {
    $page = \App\Models\Page::firstOrCreate([
        'slug' => 'about',
    ], [
        'title'    => 'Solusi Catering Profesional untuk Setiap Acara',
        'subtitle' => 'Raissa Catering hadir sebagai partner catering terpercaya di Samarinda. Kami menyediakan berbagai paket nasi kotak dan catering prasmanan untuk pernikahan, ulang tahun, meeting kantor, syukuran, dan acara keluarga.',
        'body'     => "Raissa Catering menyediakan paket catering dengan pilihan menu yang fleksibel, dari nasi kotak standar sampai paket premium untuk tamu istimewa.\n\nKami juga melayani permintaan tambahan seperti minuman, dessert, dan kebutuhan khusus menu halal.\n\nSetiap pesanan didampingi dokumentasi pesanan, sehingga Anda bisa memantau status secara online.",
    ]);

    return view('customer.about', compact('page'));
})->name('about');

// Rute daftar paket publik (Diakses oleh user/guest)
Route::get('/paket', [CustomerOrderController::class, 'packages'])->name('packages.index');
Route::get('/paket/{package:slug}', [CustomerOrderController::class, 'packageDetail'])->name('packages.show');
Route::get('/reviews/summary', [CustomerOrderController::class, 'reviewSummary'])->name('reviews.summary');

// Webhook Xendit Staging & Production (Bypass CSRF Token)
Route::post('/webhook/xendit', [WebhookController::class, 'xendit'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
    ->name('webhook.xendit');

// ============================================================
// CUSTOMER ROUTES (Harus Login / Terautentikasi)
// ============================================================
Route::middleware(['auth', 'verified'])->prefix('customer')->name('customer.')->group(function () {

    // Checkout & Pemesanan Form
    Route::get('/checkout/{package:slug}', [CustomerOrderController::class, 'checkout'])->name('checkout');
    Route::post('/orders', [CustomerOrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{orderNumber}', [CustomerOrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{orderNumber}/review', [CustomerOrderController::class, 'review'])->name('orders.review');
    Route::post('/orders/{orderNumber}/review', [CustomerOrderController::class, 'submitReview'])->name('orders.submit-review');

    // Alur Pembayaran Gateway
    Route::post('/orders/{order}/pay-dp', [CustomerOrderController::class, 'payDp'])->name('orders.pay-dp');
    Route::post('/orders/{order}/pay-full', [CustomerOrderController::class, 'payFull'])->name('orders.pay-full');
});

// ============================================================
// ADMIN ROUTES (Harus Login & Role Admin)
// ============================================================
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')->name('admin.')->group(function () {

    // Dashboard Statistika Admin
    Route::get('/', [AdminOrderController::class, 'dashboard'])->name('dashboard');

    // Rekap Laporan Keuangan & Penjualan (Diletakkan di atas resource agar tidak bentrok)
    // PERBAIKAN: Menggunakan akhiran 's' (reports) agar sinkron dengan nama folder dan sidebar admin
    Route::get('/reports', [AdminOrderController::class, 'report'])->name('reports.index');
    Route::get('/reports/export', [AdminOrderController::class, 'exportExcel'])->name('reports.export');

    // Manajemen Pesanan Masuk
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('/orders/{order}/confirm-cash', [AdminOrderController::class, 'confirmCash'])->name('orders.confirm-cash');

    // Manajemen CRUD Paket / Menu (Menggunakan Resource Controller)
    Route::resource('menus', AdminPackageController::class);

    // Manajemen Konten Halaman statis
    Route::get('/pages/about', [AdminPageController::class, 'edit'])->name('pages.edit');
    Route::patch('/pages/about', [AdminPageController::class, 'update'])->name('pages.update');
    
    // Perbaikan Toggle Status Aktif Menu
    Route::patch('/menus/{id}/toggle', [AdminPackageController::class, 'toggleActive'])->name('menus.toggle');
});

// Redirector Rute 'dashboard' bawaan sistem autentikasi Laravel Breeze
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('customer.orders.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// Pemuatan Otomatis Sistem Login/Register dari Breeze
require __DIR__.'/auth.php';