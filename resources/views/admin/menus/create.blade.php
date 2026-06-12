@extends('layouts.admin')
@section('title', 'Tambah Paket')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.menus.index') }}" class="text-gray-400 hover:text-orange-500 text-xl font-bold transition-colors">←</a>
        <h1 class="font-display text-2xl font-bold text-charcoal">Tambah Paket Baru</h1>
    </div>

    <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
            <h2 class="font-semibold text-charcoal border-b pb-2">Informasi Paket</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Paket <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500"
                    placeholder="Contoh: Paket Acara 1" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga per Kotak (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price_per_box" value="{{ old('price_per_box') }}"
                        class="w-full border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500"
                        placeholder="35000" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Order (kotak) <span class="text-red-500">*</span></label>
                    <input type="number" name="min_order" value="{{ old('min_order', 20) }}"
                        class="w-full border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Acara</label>
                <select name="event_type" class="w-full border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500">
                    @foreach(['lainnya'=>'Lainnya','pernikahan'=>'Pernikahan','ulang_tahun'=>'Ulang Tahun','meeting'=>'Meeting/Rapat','syukuran'=>'Syukuran'] as $val=>$label)
                        <option value="{{ $val }}" {{ old('event_type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3"
                    class="w-full border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500"
                    placeholder="Deskripsi singkat paket...">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Paket</label>
                <input type="file" name="image" accept="image/*"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-3">
            <h2 class="font-semibold text-charcoal border-b pb-2">Daftar Menu dalam Paket</h2>
            <p class="text-xs text-gray-400 mb-2">Isi satu item menu per baris</p>
            
            <div id="menu-items-list" class="space-y-2">
                <div class="flex gap-2">
                    <input type="text" name="menu_items[]" placeholder="Contoh: Nasi Putih"
                        class="flex-1 border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500" required>
                    <div class="w-10"></div>
                </div>
            </div>
            
            <button type="button" onclick="addMenuItem()" class="inline-block mt-2 text-orange-600 text-sm font-semibold hover:text-orange-700 hover:underline">
                + Tambah item menu
            </button>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-3">
            <h2 class="font-semibold text-charcoal border-b pb-2">Addon / Tambahan (Opsional)</h2>
            
            <div id="addons-list" class="space-y-2">
                <div class="flex gap-2">
                    <input type="text" name="addons[0][name]" placeholder="Nama addon (Tambah minuman...)"
                        class="flex-1 border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500">
                    <input type="number" name="addons[0][price]" placeholder="Harga (Rp)"
                        class="w-32 border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500">
                    <div class="w-10"></div>
                </div>
            </div>
            
            <button type="button" onclick="addAddon()" class="inline-block mt-2 text-orange-600 text-sm font-semibold hover:text-orange-700 hover:underline">
                + Tambah item addon
            </button>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-3">
            <h2 class="font-semibold text-charcoal border-b pb-2">Pilihan Menu Kustom Paket</h2>
            <p class="text-xs text-gray-400 mb-3">Centang item yang dapat dipilih pelanggan saat menggunakan fitur permintaan custom.</p>

            <div class="grid gap-4 lg:grid-cols-3">
                @foreach(['lauk' => 'Lauk', 'minuman' => 'Minuman', 'buah' => 'Buah'] as $category => $label)
                    <div class="rounded-3xl border border-gray-200 p-4 bg-gray-50">
                        <h3 class="font-semibold text-sm text-charcoal mb-3">{{ $label }}</h3>
                        <div class="space-y-2">
                            @forelse($items->where('category', $category) as $item)
                                <label class="flex items-start gap-2 rounded-2xl border border-gray-200 bg-white p-3 cursor-pointer hover:border-orange-300">
                                    <input type="checkbox" name="item_ids[]" value="{{ $item->id }}"
                                        class="mt-1 h-4 w-4 text-orange-500 rounded border-gray-300 focus:ring-orange-500"
                                        {{ in_array($item->id, old('item_ids', [])) ? 'checked' : '' }}>
                                    <div class="text-sm">
                                        <div class="font-medium text-gray-800">{{ $item->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->description }}</div>
                                        @if($item->additional_price > 0)
                                            <div class="text-xs text-orange-600 mt-1">+ Rp {{ number_format($item->additional_price, 0, ',', '.') }}</div>
                                        @endif
                                    </div>
                                </label>
                            @empty
                                <p class="text-xs text-gray-500">Belum ada item {{ strtolower($label) }} aktif.</p>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 border-t border-gray-200 pt-4">
                <h3 class="font-semibold text-sm text-charcoal mb-3">Tambah Pilihan Kustom Baru</h3>
                <div id="new-items-list" class="space-y-3">
                    @if(old('new_items'))
                        @foreach(old('new_items') as $index => $newItem)
                            <div class="new-item-row grid grid-cols-1 gap-2 lg:grid-cols-[1fr,120px,120px,80px] items-end rounded-2xl border border-gray-200 p-4 bg-white">
                                <div class="min-w-0">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Nama Item</label>
                                    <input type="text" name="new_items[{{ $index }}][name]" value="{{ $newItem['name'] }}"
                                        class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Contoh: Ayam Bakar" required>
                                </div>
                                <div class="min-w-0">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Kategori</label>
                                    <select name="new_items[{{ $index }}][category]" class="w-full border rounded-lg px-3 py-2 text-sm" required>
                                        @foreach(['lauk'=>'Lauk','minuman'=>'Minuman','buah'=>'Buah'] as $val => $label)
                                            <option value="{{ $val }}" {{ ($newItem['category'] ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="min-w-0">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Biaya Tambahan</label>
                                    <input type="number" step="0.01" name="new_items[{{ $index }}][additional_price]" value="{{ $newItem['additional_price'] ?? '' }}"
                                        class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="0">
                                </div>
                                <button type="button" onclick="this.closest('.new-item-row').remove()" class="mt-6 inline-flex items-center justify-center rounded-lg bg-red-100 text-red-600 px-3 py-2 text-sm font-semibold hover:bg-red-200">Hapus</button>
                            </div>
                        @endforeach
                    @endif
                </div>
                <button type="button" onclick="addNewItemRow()" class="inline-flex items-center gap-2 text-orange-600 text-sm font-semibold hover:text-orange-700 hover:underline">
                    + Tambah pilihan kustom baru
                </button>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-orange-500 text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-orange-600 transition-colors shadow-sm text-sm">
                Simpan Paket
            </button>
            <a href="{{ route('admin.menus.index') }}" class="border border-gray-200 px-6 py-2.5 rounded-lg text-gray-500 hover:bg-gray-50 text-sm font-medium transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    let addonCount = 1;

    function addMenuItem() {
        const container = document.getElementById('menu-items-list');
        container.insertAdjacentHTML('beforeend', `
            <div class="flex gap-2 bg-gray-50/50 p-1 rounded-lg">
                <input type="text" name="menu_items[]" placeholder="Item menu berikutnya..."
                    class="flex-1 bg-white border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500" required>
                <button type="button" onclick="this.parentElement.remove()" class="bg-red-100 text-red-500 px-3 py-2 rounded-lg text-sm font-bold hover:bg-red-200 transition-colors">×</button>
            </div>
        `);
    }

    function addAddon() {
        const container = document.getElementById('addons-list');
        container.insertAdjacentHTML('beforeend', `
            <div class="flex gap-2 bg-gray-50/50 p-1 rounded-lg">
                <input type="text" name="addons[${addonCount}][name]" placeholder="Nama addon..."
                    class="flex-1 bg-white border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500" required>
                <input type="number" name="addons[${addonCount}][price]" placeholder="Harga (Rp)"
                    class="w-32 bg-white border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500" required>
                <button type="button" onclick="this.parentElement.remove()" class="bg-red-100 text-red-500 px-3 py-2 rounded-lg text-sm font-bold hover:bg-red-200 transition-colors">×</button>
            </div>
        `);
        addonCount++;
    }

    function addNewItemRow() {
        const container = document.getElementById('new-items-list');
        const index = container.children.length;

        container.insertAdjacentHTML('beforeend', `
            <div class="new-item-row grid grid-cols-1 gap-2 lg:grid-cols-[1fr,120px,120px,80px] items-end rounded-2xl border border-gray-200 p-4 bg-white">
                <div class="min-w-0">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nama Item</label>
                    <input type="text" name="new_items[${index}][name]" class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Contoh: Ayam Bakar" required>
                </div>
                <div class="min-w-0">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Kategori</label>
                    <select name="new_items[${index}][category]" class="w-full border rounded-lg px-3 py-2 text-sm" required>
                        <option value="lauk">Lauk</option>
                        <option value="minuman">Minuman</option>
                        <option value="buah">Buah</option>
                    </select>
                </div>
                <div class="min-w-0">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Biaya Tambahan</label>
                    <input type="number" step="0.01" name="new_items[${index}][additional_price]" class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="0">
                </div>
                <button type="button" onclick="this.closest('.new-item-row').remove()" class="mt-6 inline-flex items-center justify-center rounded-lg bg-red-100 text-red-600 px-3 py-2 text-sm font-semibold hover:bg-red-200">Hapus</button>
            </div>
        `);
    }
</script>
@endsection