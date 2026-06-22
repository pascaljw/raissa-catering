<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

/**
 * CONTOH PENGGUNAAN CUSTOM VALIDATION RULE
 * 
 * File ini menunjukkan bagaimana menggunakan CreateOrderRequest
 * dengan custom validation rule ValidateDeliveryDateByQuantity
 */

class OrderController extends Controller
{
    /**
     * Menyimpan pesanan baru dengan validasi custom
     */
    public function store(CreateOrderRequest $request)
    {
        // Validasi sudah dilakukan otomatis oleh CreateOrderRequest
        // Jika ada error, Laravel akan redirect kembali dengan pesan error

        try {
            DB::beginTransaction();

            // Data yang sudah tervalidasi
            $validated = $request->validated();

            // Hitung harga dan tambahan
            $package = $request->package()->first();
            $quantity = $validated['quantity'];
            $pricePerBox = $package->price;
            $subtotal = $quantity * $pricePerBox;

            // Hitung addon total
            $addonTotal = 0;
            if (!empty($validated['selected_addons'])) {
                $addonTotal = DB::table('package_addons')
                    ->whereIn('id', $validated['selected_addons'])
                    ->sum('price');
            }

            $totalAmount = $subtotal + $addonTotal;
            $dpAmount = $totalAmount * 0.5; // 50% DP

            // Buat order number
            $orderNumber = $this->generateOrderNumber();

            // Simpan ke database
            $order = Order::create([
                'order_number'   => $orderNumber,
                'user_id'        => auth()->id(),
                'package_id'     => $validated['package_id'],
                'quantity'       => $quantity,
                'price_per_box'  => $pricePerBox,
                'subtotal'       => $subtotal,
                'addon_total'    => $addonTotal,
                'total_amount'   => $totalAmount,
                'dp_amount'      => $dpAmount,
                'remaining_amount' => $totalAmount - $dpAmount,
                'event_name'     => $validated['event_name'],
                'event_location' => $validated['event_location'],
                'event_address'  => $validated['event_address'],
                'event_date'     => $validated['event_date'],
                'delivery_time'  => $validated['delivery_time'],
                'selected_addons' => $validated['selected_addons'] ?? [],
                'contact_name'   => $validated['contact_name'],
                'contact_phone'  => $validated['contact_phone'],
                'notes'          => $validated['notes'] ?? null,
                'status'         => 'pending',
                'payment_status' => 'unpaid',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat.',
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate order number dengan format RC-YYYYMMDD-XXXX
     */
    private function generateOrderNumber(): string
    {
        $today = now()->format('Ymd');
        $lastOrder = Order::whereDate('created_at', now())
            ->latest('id')
            ->first();

        $sequence = ($lastOrder ? (int)substr($lastOrder->order_number, -4) + 1 : 1);
        return 'RC-' . $today . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
