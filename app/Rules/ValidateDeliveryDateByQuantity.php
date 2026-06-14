<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateDeliveryDateByQuantity implements ValidationRule
{
    /**
     * Quantity dari pesanan
     */
    private int $quantity;

    public function __construct(int $quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $deliveryDate = Carbon::parse($value);
        $today = Carbon::today();
        $minDeliveryDate = $today->copy()->addDays(1); // Standar minimum H+1

        // Jika quantity >= 100, maka minimum delivery_date adalah H+5
        if ($this->quantity >= 100) {
            $minDeliveryDate = $today->copy()->addDays(5);
            
            if ($deliveryDate->isBefore($minDeliveryDate)) {
                $fail('Untuk pesanan 100 kotak atau lebih, tanggal pengiriman minimal harus ' . $minDeliveryDate->format('d F Y') . ' (H+5).');
            }
        } else {
            // Untuk pesanan kurang dari 100 kotak, minimal H+1
            if ($deliveryDate->isBefore($minDeliveryDate)) {
                $fail('Tanggal pengiriman minimal harus ' . $minDeliveryDate->format('d F Y') . ' (H+1).');
            }
        }

        // Tambahan: Pastikan delivery date tidak boleh terlalu jauh di masa depan (optional)
        // Misalnya, tidak boleh lebih dari 90 hari ke depan
        $maxDeliveryDate = $today->copy()->addDays(90);
        if ($deliveryDate->isAfter($maxDeliveryDate)) {
            $fail('Tanggal pengiriman tidak boleh lebih dari 90 hari ke depan.');
        }
    }
}
