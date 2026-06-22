<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'package_id' => ['required', 'integer', 'exists:packages,id'],
            'selected_items' => ['required', 'array', 'min:1'],
            'selected_items.*.item_id' => ['required', 'integer', 'exists:items,id'],
            'selected_items.*.category' => ['required', 'string', 'in:lauk,minuman,buah'],
            'selected_items.*.quantity' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'package_id.required' => 'Paket harus dipilih.',
            'selected_items.required' => 'Pilih minimal satu item menu.',
            'selected_items.*.item_id.exists' => 'Item yang dipilih tidak valid.',
            'selected_items.*.category.in' => 'Kategori item harus lauk, minuman, atau buah.',
        ];
    }
}
