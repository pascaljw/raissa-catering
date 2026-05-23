@extends('layouts.admin')
@section('title', 'Edit Paket')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.menus.index') }}" class="text-gray-400 hover:text-orange-500 text-xl font-bold transition-colors">←</a>
        <h1 class="font-display text-2xl font-bold text-charcoal">Edit: {{ $package->name }}</h1>
    </div>

    <form action="{{ route('admin.menus.update', $package->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf 
        @method('PUT')

        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
            <h2 class="font-semibold text-charcoal border-b pb-2">Informasi Paket</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Paket</label>
                <input type="text" name="name" value="{{ old('name', $package->name) }}"
                    class="w-full border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga per Kotak (Rp)</label>
                    <input type="number" name="price_per_box" value="{{ old('price_per_box', $package->price_per_box) }}"
                        class="w-full border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Order</label>
                    <input type="number" name="min_order" value="{{ old('min_order', $package->min_order) }}"
                        class="w-full border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Acara</label>
                <select name="event_type" class="w-full border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500">
                    @foreach(['lainnya'=>'Lainnya','pernikahan'=>'Pernikahan','ulang_tahun'=>'Ulang Tahun','meeting'=>'Meeting/Rapat','syukuran'=>'Syukuran'] as $val=>$label)
                        <option value="{{ $val }}" {{ (old('event_type', $package->event_type) === $val) ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3"
                    class="w-full border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500" required>{{ old('description', $package->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Paket</label>
                @if($package->image)
                    <div class="mb-2 flex items-center gap-3">
                        <img src="{{ $package->image_url }}" class="w-32 h-24 object-cover rounded-lg border shadow-sm">
                        <label class="inline-flex items-center gap-2 text-sm text-red-600 cursor-pointer">
                            <input type="checkbox" name="remove_image" value="1" class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            Hapus foto paket
                        </label>
                    </div>
                @endif
                <input type="file" name="image" accept="image/*"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengubah foto. Centang hapus foto bila ingin menghapus gambar lama tanpa mengganti.</p>
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" name="is_active" value="1" id="is_active"
                    {{ old('is_active', $package->is_active) ? 'checked' : '' }}
                    class="w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                <label for="is_active" class="text-sm font-medium text-gray-700 select-none">Paket aktif (tampil di website)</label>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-3">
            <h2 class="font-semibold text-charcoal border-b pb-2">Daftar Menu</h2>
            <p class="text-xs text-gray-400 mb-2">Isi satu item menu per baris</p>
            
            <div id="menu-items-list" class="space-y-2">
                @forelse(old('menu_items', $package->menu_items ?? []) as $item)
                <div class="flex gap-2">
                    <input type="text" name="menu_items[]" value="{{ $item }}"
                        class="flex-1 border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500" required>
                    <button type="button" onclick="this.parentElement.remove()" class="bg-red-100 text-red-500 px-3 py-2 rounded-lg text-sm font-bold hover:bg-red-200 transition-colors">×</button>
                </div>
                @empty
                <div class="flex gap-2">
                    <input type="text" name="menu_items[]" placeholder="Contoh: Nasi Goreng"
                        class="flex-1 border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500" required>
                    <div class="w-10"></div>
                </div>
                @endforelse
            </div>
            
            <button type="button" onclick="addMenuItem()" class="inline-block mt-2 text-orange-600 text-sm font-semibold hover:text-orange-700 hover:underline">
                + Tambah item menu
            </button>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-orange-500 text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-orange-600 transition-colors shadow-sm text-sm">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.menus.index') }}" class="border border-gray-200 px-6 py-2.5 rounded-lg text-gray-500 hover:bg-gray-50 text-sm font-medium transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    function addMenuItem() {
        const container = document.getElementById('menu-items-list');
        container.insertAdjacentHTML('beforeend', `
            <div class="flex gap-2 bg-gray-50/50 p-1 rounded-lg">
                <input type="text" name="menu_items[]" placeholder="Contoh: Es Teh Manis..."
                    class="flex-1 bg-white border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500" required>
                <button type="button" onclick="this.parentElement.remove()" class="bg-red-100 text-red-500 px-3 py-2 rounded-lg text-sm font-bold hover:bg-red-200 transition-colors">×</button>
            </div>
        `);
    }
</script>
@endsection