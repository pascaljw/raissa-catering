@props([
    'basePrice' => 0,
    'laukItems' => [],
    'drinkItems' => [],
    'fruitItems' => [],
])

<div id="pos-custom-menu" class="max-w-5xl mx-auto bg-white border border-gray-200 rounded-3xl shadow-sm p-6 lg:p-8" data-base-price="{{ $basePrice }}">
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-slate-900">Pilih Menu Kustom</h2>
        <p class="text-sm text-slate-500 mt-1">Pilih satu atau lebih item dari kategori lauk, minuman, dan buah untuk hitungan POS.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <fieldset class="space-y-4 rounded-3xl border border-gray-200 p-5">
            <legend class="text-sm font-semibold text-slate-900">Lauk</legend>
            <div class="space-y-3">
                @forelse($laukItems as $item)
                <label class="grid w-full grid-cols-[minmax(0,1fr),auto] gap-3 rounded-2xl border border-slate-200 p-4 cursor-pointer hover:border-primary/60 transition-colors">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2 text-sm font-medium text-slate-900">
                            <span class="break-words">{{ $item['name'] }}</span>
                            @if(!empty($item['additional_price']) && $item['additional_price'] > 0)
                            <span class="flex-shrink-0 rounded-full bg-orange-100 text-orange-700 px-2 py-0.5 text-[11px] font-semibold whitespace-nowrap">+ Rp {{ number_format($item['additional_price'], 0, ',', '.') }}</span>
                            @endif
                        </div>
                        @if(!empty($item['description']))
                        <p class="text-xs text-slate-500 mt-1 break-words">{{ $item['description'] }}</p>
                        @endif
                    </div>
                    <div class="flex items-start">
                        <input type="checkbox" name="selected_items[]" value="{{ $item['id'] }}" data-category="lauk" data-name="{{ $item['name'] }}" data-add-price="{{ $item['additional_price'] ?? 0 }}" class="h-5 w-5 text-primary border-slate-300 focus:ring-primary">
                    </div>
                </label>
                @empty
                <p class="text-sm text-slate-500">Tidak ada lauk tersedia untuk paket ini.</p>
                @endforelse
            </div>
        </fieldset>

        <fieldset class="space-y-4 rounded-3xl border border-gray-200 p-5">
            <legend class="text-sm font-semibold text-slate-900">Minuman</legend>
            <div class="space-y-3">
                @forelse($drinkItems as $item)
                <label class="grid w-full grid-cols-[minmax(0,1fr),auto] gap-3 rounded-2xl border border-slate-200 p-4 cursor-pointer hover:border-primary/60 transition-colors">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2 text-sm font-medium text-slate-900">
                            <span class="break-words">{{ $item['name'] }}</span>
                            @if(!empty($item['additional_price']) && $item['additional_price'] > 0)
                            <span class="flex-shrink-0 rounded-full bg-orange-100 text-orange-700 px-2 py-0.5 text-[11px] font-semibold whitespace-nowrap">+ Rp {{ number_format($item['additional_price'], 0, ',', '.') }}</span>
                            @endif
                        </div>
                        @if(!empty($item['description']))
                        <p class="text-xs text-slate-500 mt-1 break-words">{{ $item['description'] }}</p>
                        @endif
                    </div>
                    <div class="flex items-start">
                        <input type="checkbox" name="selected_items[]" value="{{ $item['id'] }}" data-category="minuman" data-name="{{ $item['name'] }}" data-add-price="{{ $item['additional_price'] ?? 0 }}" class="h-5 w-5 text-primary border-slate-300 focus:ring-primary">
                    </div>
                </label>
                @empty
                <p class="text-sm text-slate-500">Tidak ada minuman tersedia untuk paket ini.</p>
                @endforelse
            </div>
        </fieldset>

        <fieldset class="space-y-4 rounded-3xl border border-gray-200 p-5">
            <legend class="text-sm font-semibold text-slate-900">Buah</legend>
            <div class="space-y-3">
                @forelse($fruitItems as $item)
                <label class="grid w-full grid-cols-[minmax(0,1fr),auto] gap-3 rounded-2xl border border-slate-200 p-4 cursor-pointer hover:border-primary/60 transition-colors">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2 text-sm font-medium text-slate-900">
                            <span class="break-words">{{ $item['name'] }}</span>
                            @if(!empty($item['additional_price']) && $item['additional_price'] > 0)
                            <span class="flex-shrink-0 rounded-full bg-orange-100 text-orange-700 px-2 py-0.5 text-[11px] font-semibold whitespace-nowrap">+ Rp {{ number_format($item['additional_price'], 0, ',', '.') }}</span>
                            @endif
                        </div>
                        @if(!empty($item['description']))
                        <p class="text-xs text-slate-500 mt-1 break-words">{{ $item['description'] }}</p>
                        @endif
                    </div>
                    <div class="flex items-start">
                        <input type="checkbox" name="selected_items[]" value="{{ $item['id'] }}" data-category="buah" data-name="{{ $item['name'] }}" data-add-price="{{ $item['additional_price'] ?? 0 }}" class="h-5 w-5 text-primary border-slate-300 focus:ring-primary">
                    </div>
                </label>
                @empty
                <p class="text-sm text-slate-500">Tidak ada buah tersedia untuk paket ini.</p>
                @endforelse
            </div>
        </fieldset>
    </div>

    <div class="mt-8 rounded-3xl border border-slate-200 bg-slate-50 p-5 lg:p-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Ringkasan POS</p>
                <p class="text-sm text-slate-500">Harga dasar paket + biaya tambahan item kustom.</p>
            </div>
            <div class="space-y-1 text-right">
                <p class="text-sm text-slate-500">Harga Dasar Paket</p>
                <p class="text-2xl font-semibold text-slate-900">Rp {{ number_format($basePrice, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="mt-6 grid gap-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <div class="flex items-center justify-between text-sm text-slate-500 mb-2">
                    <span>Item terpilih</span>
                    <span class="font-medium text-slate-700" data-pos-item-count>0 pilihan</span>
                </div>
                <div class="space-y-2 text-sm text-slate-700" data-pos-selected-items>
                    <p class="text-slate-500">Belum ada pilihan.</p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-5 flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Total Harga</p>
                    <p class="text-xs text-slate-400">Termasuk harga dasar paket</p>
                </div>
                <p class="text-3xl font-bold text-slate-900" data-pos-total>Rp {{ number_format($basePrice, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const root = document.getElementById('pos-custom-menu');
    if (!root) return;

    const basePrice = parseFloat(root.dataset.basePrice) || 0;
    const totalNode = root.querySelector('[data-pos-total]');
    const itemsNode = root.querySelector('[data-pos-selected-items]');
    const countNode = root.querySelector('[data-pos-item-count]');
    const inputs = Array.from(root.querySelectorAll('input[type="checkbox"]'));

    const formatCurrency = amount => new Intl.NumberFormat('id-ID', {
        style: 'currency', currency: 'IDR', maximumFractionDigits: 0
    }).format(amount).replace('Rp', 'Rp ');

    const updateSummary = () => {
        const selected = inputs.filter(input => input.checked);
        let total = basePrice;

        if (selected.length === 0) {
            itemsNode.innerHTML = '<p class="text-slate-500">Belum ada pilihan.</p>';
            countNode.textContent = '0 pilihan';
            totalNode.textContent = formatCurrency(total);
            return;
        }

        const lines = selected.map(input => {
            const name = input.dataset.name || 'Item';
            const addPrice = parseFloat(input.dataset.addPrice || '0');
            total += addPrice;
            return `<div class="flex items-center justify-between rounded-2xl bg-slate-50 px-3 py-2">
                        <span>${input.dataset.category.toUpperCase()}: ${name}</span>
                        <span class="text-slate-700">${addPrice ? '+ ' + formatCurrency(addPrice) : 'Rp 0'}</span>
                    </div>`;
        });

        itemsNode.innerHTML = lines.join('');
        countNode.textContent = `${selected.length} pilihan`;
        totalNode.textContent = formatCurrency(total);
    };

    inputs.forEach(input => input.addEventListener('change', updateSummary));
    updateSummary();
})();
</script>
