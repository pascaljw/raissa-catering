<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PageController extends Controller
{
    public function edit()
    {
        if (! Schema::hasTable('pages')) {
            abort(503, 'Tabel halaman belum tersedia. Jalankan migrasi terlebih dahulu.');
        }

        $page = Page::firstOrCreate([
            'slug' => 'about',
        ], [
            'title'    => 'Solusi Catering Profesional untuk Setiap Acara',
            'subtitle' => 'Raissa Catering hadir sebagai partner catering terpercaya di Samarinda. Kami menyediakan berbagai paket nasi kotak dan catering prasmanan untuk pernikahan, ulang tahun, meeting kantor, syukuran, dan acara keluarga.',
            'body'     => "Raissa Catering menyediakan paket catering dengan pilihan menu yang fleksibel, dari nasi kotak standar sampai paket premium untuk tamu istimewa.\n\nKami juga melayani permintaan tambahan seperti minuman, dessert, dan kebutuhan khusus menu halal.\n\nSetiap pesanan didampingi dokumentasi pesanan, sehingga Anda bisa memantau status secara online.",
        ]);

        $bodyForEdit = $this->normalizeBody($page->body);

        return view('admin.pages.edit', compact('page', 'bodyForEdit'));
    }

    public function update(Request $request)
    {
        $page = Page::where('slug', 'about')->firstOrFail();

        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:1000',
            'body'     => 'nullable|string',
        ]);

        $page->update($data);

        return back()->with('success', 'Konten Tentang Kami berhasil diperbarui.');
    }

    private function normalizeBody(?string $body): string
    {
        if (! $body) {
            return '';
        }

        $body = preg_replace('/<\s*\/\s*p\s*>\s*<\s*p\s*>/i', "\n\n", $body);
        $body = preg_replace('/<\s*br\s*\/?\s*>/i', "\n", $body);
        $body = strip_tags($body);

        return trim($body);
    }
}
