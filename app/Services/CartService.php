<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Package;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    public const SESSION_KEY = 'cart.custom_menu';

    public function addPackageToCart(array $data): array
    {
        $package = Package::with('items')->findOrFail($data['package_id']);

        $selectedItems = collect($data['selected_items'] ?? []);
        if ($selectedItems->isEmpty()) {
            throw new \InvalidArgumentException('Pilih minimal satu item dari kategori lauk, minuman, atau buah.');
        }

        $availableItemIds = $package->items->pluck('id');
        $invalidItemIds = $selectedItems->pluck('item_id')->diff($availableItemIds);
        if ($invalidItemIds->isNotEmpty()) {
            throw new \InvalidArgumentException('Pilihan item tidak tersedia untuk paket yang dipilih.');
        }

        $items = Item::whereIn('id', $selectedItems->pluck('item_id'))->get()->keyBy('id');

        $lineItems = $selectedItems->map(function (array $selected) use ($items, $package) {
            $item = $items->get($selected['item_id']);

            if (! $item) {
                throw new \InvalidArgumentException(sprintf('Item dengan ID %s tidak ditemukan.', $selected['item_id']));
            }

            if ($item->category !== $selected['category']) {
                throw new \InvalidArgumentException(sprintf('Kategori item %s tidak sesuai.', $item->name));
            }

            if (! in_array($item->category, ['lauk', 'minuman', 'buah'], true)) {
                throw new \InvalidArgumentException('Kategori item tidak valid.');
            }

            $quantity = isset($selected['quantity']) ? max(1, intval($selected['quantity'])) : 1;
            $additionalPrice = $item->additional_price * $quantity;

            return [
                'item_id'          => $item->id,
                'item_name'        => $item->name,
                'category'         => $item->category,
                'quantity'         => $quantity,
                'unit_price'       => $item->additional_price,
                'additional_price' => $additionalPrice,
                'total_price'      => $additionalPrice,
            ];
        });

        $additionalTotal = $lineItems->sum('additional_price');
        $basePrice = $package->price_per_box;
        $totalPrice = $basePrice + $additionalTotal;

        $cart = [
            'package_id'       => $package->id,
            'package_name'     => $package->name,
            'base_price'       => $basePrice,
            'selected_items'   => $lineItems->toArray(),
            'additional_total' => $additionalTotal,
            'total_price'      => $totalPrice,
            'created_at'       => now()->toDateTimeString(),
        ];

        Session::put(self::SESSION_KEY, $cart);

        return $cart;
    }

    public function getCart(): ?array
    {
        return Session::get(self::SESSION_KEY);
    }

    public function clearCart(): void
    {
        Session::forget(self::SESSION_KEY);
    }
}
