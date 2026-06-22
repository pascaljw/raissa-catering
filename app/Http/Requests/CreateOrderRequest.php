<?php

namespace App\Http\Requests;

use App\Rules\ValidateDeliveryDateByQuantity;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $quantity = (int) $this->input('quantity', 0);

        return [
            // Validasi Dasar
            'package_id'        => ['required', 'integer', 'exists:packages,id'],
            'quantity'          => ['required', 'integer', 'min:1', 'max:500'],
            
            // Validasi Tanggal Pengiriman dengan Custom Rule
            'event_date'        => [
                'required', 
                'date_format:Y-m-d',
                new ValidateDeliveryDateByQuantity($quantity)
            ],
            
            // Validasi Detail Acara
            'event_name'        => ['required', 'string', 'max:255'],
            'event_location'    => ['required', 'string', 'max:255'],
            'event_address'     => ['required', 'string', 'max:500'],
            'delivery_time'     => ['required', 'date_format:H:i'],
            
            // Validasi Kontak
            'contact_name'      => ['required', 'string', 'max:255'],
            'contact_phone'     => ['required', 'string', 'regex:/^(\+62|62|0)[0-9]{9,12}$/'],
            
            // Validasi Opsional
            'selected_addons'   => ['nullable', 'array'],
            'selected_addons.*' => ['integer', 'exists:package_addons,id'],
            'notes'             => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            // Pesan untuk Package
            'package_id.required' => 'Paket harus dipilih.',
            'package_id.exists'   => 'Paket yang dipilih tidak valid.',
            
            // Pesan untuk Quantity
            'quantity.required' => 'Jumlah pesanan (kotak) harus diisi.',
            'quantity.integer'  => 'Jumlah pesanan harus berupa angka bulat.',
            'quantity.min'      => 'Minimal pesanan adalah 1 kotak.',
            'quantity.max'      => 'Maksimal pesanan adalah 500 kotak.',
            
            // Pesan untuk Event Date (Delivery Date)
            'event_date.required'    => 'Tanggal pengiriman harus diisi.',
            'event_date.date_format' => 'Format tanggal pengiriman harus YYYY-MM-DD.',
            
            // Pesan untuk Detail Acara
            'event_name.required'    => 'Nama acara harus diisi.',
            'event_name.max'         => 'Nama acara maksimal 255 karakter.',
            'event_location.required' => 'Lokasi acara harus diisi.',
            'event_location.max'     => 'Lokasi acara maksimal 255 karakter.',
            'event_address.required' => 'Alamat acara harus diisi.',
            'event_address.max'      => 'Alamat acara maksimal 500 karakter.',
            'delivery_time.required' => 'Waktu pengiriman harus diisi.',
            'delivery_time.date_format' => 'Format waktu pengiriman harus HH:MM.',
            
            // Pesan untuk Kontak
            'contact_name.required'  => 'Nama kontak harus diisi.',
            'contact_name.max'       => 'Nama kontak maksimal 255 karakter.',
            'contact_phone.required' => 'Nomor telepon harus diisi.',
            'contact_phone.regex'    => 'Nomor telepon harus valid (dimulai dari +62, 62, atau 0).',
            
            // Pesan untuk Add-ons
            'selected_addons.array' => 'Add-ons harus berupa array.',
            'selected_addons.*.integer' => 'ID add-on harus berupa angka.',
            'selected_addons.*.exists'  => 'Add-on yang dipilih tidak valid.',
            
            // Pesan untuk Notes
            'notes.max' => 'Catatan maksimal 1000 karakter.',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     * Ini method untuk custom error handling jika diperlukan
     */
    public function withValidator($validator)
    {
        // Bisa tambahkan custom validation logic di sini jika perlu
        $validator->after(function ($validator) {
            // Custom logic akan ditambahkan di sini
        });
    }
}
