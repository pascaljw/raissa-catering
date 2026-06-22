<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Package;
use App\Models\PackageAddon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::withCount('orders')->orderBy('sort_order')->get();
        return view('admin.menus.index', compact('packages'));
    }

    public function create()
    {
        $items = Item::where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        return view('admin.menus.create', compact('items'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                => 'required|string|max:255',
            'description'         => 'nullable|string',
            'price_per_box'       => 'required|numeric|min:1000',
            'min_order'           => 'required|integer|min:1',
            'event_type'          => 'required|in:pernikahan,ulang_tahun,meeting,syukuran,lainnya',
            'menu_items'          => 'nullable|array',
            'menu_items.*'        => 'string|max:100',
            'item_ids'            => 'nullable|array',
            'item_ids.*'          => 'integer|exists:items,id',
            'new_items'           => 'nullable|array',
            'new_items.*.name'    => 'required_with:new_items|string|max:100',
            'new_items.*.category'=> 'required_with:new_items|in:lauk,minuman,buah',
            'new_items.*.additional_price' => 'nullable|numeric|min:0',
            'new_items.*.description' => 'nullable|string|max:255',
            'image'               => 'nullable|image|max:2048',
            'sort_order'          => 'nullable|integer',
            'addons'              => 'nullable|array',
            'addons.*.name'       => 'required|string|max:100',
            'addons.*.price'      => 'required|numeric|min:0',
        ]);

        $data['slug'] = Str::slug($request->name) . '-' . uniqid();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('packages', 'public');
        }

        $package = Package::create($data);

        if ($request->addons) {
            foreach ($request->addons as $addon) {
                $package->addons()->create($addon);
            }
        }

        $this->syncPackageItems($package, $data);

        return redirect()->route('admin.menus.index')->with('success', 'Paket berhasil ditambahkan.');
    }

    /**
     * PERBAIKAN: Menggunakan $id manual untuk menghindari error Route Binding
     */
    public function edit($id)
    {
        $package = Package::findOrFail($id);
        $package->load('addons', 'items');

        $items = Item::where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        return view('admin.menus.edit', compact('package', 'items'));
    }

    /**
     * PERBAIKAN: Menggunakan $id manual dan memperbaiki handling checkbox is_active
     */
    public function update(Request $request, $id)
    {
        $package = Package::findOrFail($id);

        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price_per_box' => 'required|numeric|min:1000',
            'min_order'     => 'required|integer|min:1',
            'event_type'    => 'required|in:pernikahan,ulang_tahun,meeting,syukuran,lainnya',
            'menu_items'    => 'nullable|array',
            'menu_items.*'  => 'string|max:100',
            'item_ids'      => 'nullable|array',
            'item_ids.*'    => 'integer|exists:items,id',
            'new_items'     => 'nullable|array',
            'new_items.*.name'    => 'required_with:new_items|string|max:100',
            'new_items.*.category'=> 'required_with:new_items|in:lauk,minuman,buah',
            'new_items.*.additional_price' => 'nullable|numeric|min:0',
            'new_items.*.description' => 'nullable|string|max:255',
            'image'         => 'nullable|image|max:2048',
            'remove_image'  => 'nullable|boolean',
            'sort_order'    => 'nullable|integer',
        ]);

        // Karena checkbox jika tidak dicentang tidak mengirimkan data, maka kita set manual
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->boolean('remove_image') && $package->image) {
            Storage::disk('public')->delete($package->image);
            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            if ($package->image) {
                Storage::disk('public')->delete($package->image);
            }
            $data['image'] = $request->file('image')->store('packages', 'public');
        }

        $package->update($data);
        $this->syncPackageItems($package, $data);

        return redirect()->route('admin.menus.index')->with('success', 'Paket berhasil diperbarui.');
    }

    /**
     * PERBAIKAN: Menggunakan $id manual untuk proses hapus paket
     */
    public function destroy($id)
    {
        $package = Package::findOrFail($id);

        if ($package->orders()->whereNotIn('status', ['cancelled'])->exists()) {
            return back()->withErrors(['delete' => 'Tidak dapat menghapus paket yang memiliki pesanan aktif.']);
        }
        
        if ($package->image) {
            Storage::disk('public')->delete($package->image);
        }
        
        $package->delete();
        return back()->with('success', 'Paket berhasil dihapus.');
    }

    /**
     * PERBAIKAN: Menggunakan $id manual untuk status toggle active via card index
     */
    public function toggleActive($id)
    {
        $package = Package::findOrFail($id);
        $package->update(['is_active' => !$package->is_active]);
        
        return back()->with('success', 'Status paket diperbarui.');
    }

    protected function syncPackageItems(Package $package, array $data): void
    {
        $itemIds = $data['item_ids'] ?? [];

        if (! empty($data['new_items'])) {
            foreach ($data['new_items'] as $newItem) {
                if (empty($newItem['name']) || empty($newItem['category'])) {
                    continue;
                }

                $createdItem = Item::create([
                    'name'             => $newItem['name'],
                    'category'         => $newItem['category'],
                    'additional_price' => $newItem['additional_price'] ?? 0,
                    'description'      => $newItem['description'] ?? null,
                    'is_active'        => true,
                ]);

                $itemIds[] = $createdItem->id;
            }
        }

        $package->items()->sync(array_values(array_filter($itemIds, fn($id) => is_numeric($id))));
    }
}