<?php

/**
 * ALTERNATIF PENGGUNAAN: Tanpa Form Request
 * 
 * Jika Anda ingin menggunakan custom rule ini tanpa Form Request,
 * Anda bisa menggunakan method validate() di Controller secara langsung.
 */

// ============================================================
// CONTOH 1: Menggunakan Validator::make() di Controller
// ============================================================

namespace App\Http\Controllers;

use App\Models\Order;
use App\Rules\ValidateDeliveryDateByQuantity;
use Illuminate\Support\Facades\Validator;

class OrderControllerAlternative extends Controller
{
    /**
     * Simpan order dengan validator manual
     */
    public function storeWithValidatorMake()
    {
        $data = request()->all();
        
        $validator = Validator::make($data, [
            'package_id' => ['required', 'integer', 'exists:packages,id'],
            'quantity'   => ['required', 'integer', 'min:1'],
            // Gunakan custom rule di sini
            'event_date' => [
                'required',
                'date_format:Y-m-d',
                new ValidateDeliveryDateByQuantity((int)$data['quantity'])
            ],
            'event_name' => ['required', 'string'],
            'contact_phone' => ['required', 'regex:/^(\+62|62|0)[0-9]{9,12}$/'],
        ], [
            'event_date.required' => 'Tanggal pengiriman harus diisi.',
            'event_date.date_format' => 'Format tanggal harus YYYY-MM-DD.',
            'contact_phone.regex' => 'Nomor telepon tidak valid.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Lanjutkan proses penyimpanan
        $validated = $validator->validated();
        // ... proses data
    }
}

// ============================================================
// CONTOH 2: Menggunakan withValidator() dengan Form Request
// ============================================================

namespace App\Http\Requests;

use App\Rules\ValidateDeliveryDateByQuantity;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequestWithCustomValidation extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $quantity = (int)$this->input('quantity', 0);

        return [
            'package_id' => ['required', 'integer', 'exists:packages,id'],
            'quantity'   => ['required', 'integer', 'min:1'],
            'event_date' => [
                'required',
                'date_format:Y-m-d',
                new ValidateDeliveryDateByQuantity($quantity)
            ],
        ];
    }

    /**
     * Jalankan validasi custom setelah validation rules
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Anda bisa tambahkan logic validasi tambahan di sini
            
            // Contoh: Cek apakah date_event sudah pernah digunakan user
            $existingOrder = \App\Models\Order::where('user_id', auth()->id())
                ->where('event_date', $this->event_date)
                ->exists();

            if ($existingOrder) {
                $validator->errors()->add(
                    'event_date',
                    'Anda sudah memiliki pesanan pada tanggal tersebut.'
                );
            }

            // Contoh: Cek apakah event_date bukan hari libur tertentu
            $blockedDates = \App\Models\BlockedDate::pluck('date')->toArray();
            if (in_array($this->event_date, $blockedDates)) {
                $validator->errors()->add(
                    'event_date',
                    'Tanggal tersebut tidak tersedia (hari libur).'
                );
            }
        });
    }
}

// ============================================================
// CONTOH 3: Membuat Rule dengan Pesan Custom Dinamis
// ============================================================

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateDeliveryDateByQuantityAdvanced implements ValidationRule
{
    private int $quantity;
    private string $customErrorMessage = '';

    public function __construct(int $quantity)
    {
        $this->quantity = $quantity;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $deliveryDate = Carbon::parse($value);
        $today = Carbon::today();
        $minDeliveryDate = $today->copy()->addDays(1);

        if ($this->quantity > 100) {
            $minDeliveryDate = $today->copy()->addDays(5);
            
            if ($deliveryDate->isBefore($minDeliveryDate)) {
                $formattedDate = $minDeliveryDate->locale('id')->format('d F Y');
                $daysDifference = $minDeliveryDate->diffInDays($deliveryDate, false);
                
                $this->customErrorMessage = sprintf(
                    'Pesanan Anda (%d kotak) termasuk pesanan besar. ' .
                    'Tanggal pengiriman minimal harus %s (%s dari hari ini). ' .
                    'Saat ini Anda memilih %s yang kurang %d hari.',
                    $this->quantity,
                    $formattedDate,
                    'H+5',
                    $deliveryDate->locale('id')->format('d F Y'),
                    abs($daysDifference)
                );
                
                $fail($this->customErrorMessage);
            }
        } else {
            if ($deliveryDate->isBefore($minDeliveryDate)) {
                $formattedDate = $minDeliveryDate->locale('id')->format('d F Y');
                
                $this->customErrorMessage = sprintf(
                    'Tanggal pengiriman minimal harus %s (%s dari hari ini).',
                    $formattedDate,
                    'H+1'
                );
                
                $fail($this->customErrorMessage);
            }
        }

        $maxDeliveryDate = $today->copy()->addDays(90);
        if ($deliveryDate->isAfter($maxDeliveryDate)) {
            $this->customErrorMessage = 'Tanggal pengiriman tidak boleh lebih dari 90 hari ke depan.';
            $fail($this->customErrorMessage);
        }
    }

    /**
     * Getter untuk mengakses pesan error dari luar
     */
    public function getErrorMessage(): string
    {
        return $this->customErrorMessage;
    }
}

// ============================================================
// CONTOH 4: Menggunakan di Frontend (JavaScript)
// ============================================================

/*
 * Contoh validasi client-side sebelum mengirim ke server
 */

class OrderValidation {
    constructor() {
        this.today = new Date();
        this.today.setHours(0, 0, 0, 0);
    }

    validateDeliveryDate(quantity, deliveryDateString) {
        const deliveryDate = new Date(deliveryDateString);
        deliveryDate.setHours(0, 0, 0, 0);

        // Tentukan minimum delivery date
        let minDaysToAdd = quantity > 100 ? 5 : 1;
        const minDeliveryDate = new Date(this.today);
        minDeliveryDate.setDate(minDeliveryDate.getDate() + minDaysToAdd);

        // Tentukan maksimum delivery date
        const maxDeliveryDate = new Date(this.today);
        maxDeliveryDate.setDate(maxDeliveryDate.getDate() + 90);

        // Validasi
        if (deliveryDate < minDeliveryDate) {
            const daysShort = Math.ceil((minDeliveryDate - deliveryDate) / (1000 * 60 * 60 * 24));
            return {
                valid: false,
                message: quantity > 100
                    ? `Pesanan ${quantity} kotak membutuhkan H+5. Kurang ${daysShort} hari.`
                    : `Tanggal pengiriman minimal H+1. Kurang ${daysShort} hari.`
            };
        }

        if (deliveryDate > maxDeliveryDate) {
            return {
                valid: false,
                message: 'Tanggal pengiriman tidak boleh lebih dari 90 hari ke depan.'
            };
        }

        return {
            valid: true,
            message: 'Tanggal pengiriman valid.'
        };
    }
}

// Penggunaan di JavaScript
const validator = new OrderValidation();

// Test dengan 150 kotak
const result = validator.validateDeliveryDate(150, '2026-06-20');
console.log(result); // { valid: true, message: '...' }

// ============================================================
// CONTOH 5: Testing dengan PHPUnit
// ============================================================

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Package;
use App\Models\User;
use Tests\TestCase;

class CreateOrderTest extends TestCase
{
    protected User $user;
    protected Package $package;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->package = Package::factory()->create();
    }

    /**
     * Test pemesanan 150 kotak dengan H+5
     */
    public function test_create_order_with_150_boxes_and_h_plus_5_is_valid()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/orders', [
                'package_id' => $this->package->id,
                'quantity' => 150,
                'event_date' => now()->addDays(5)->format('Y-m-d'),
                'event_name' => 'Pernikahan',
                'event_location' => 'Jakarta',
                'event_address' => 'Jl. Merdeka',
                'delivery_time' => '10:30',
                'contact_name' => 'Ahmad',
                'contact_phone' => '08123456789',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'quantity' => 150,
        ]);
    }

    /**
     * Test pemesanan 150 kotak dengan H+3 (invalid)
     */
    public function test_create_order_with_150_boxes_and_h_plus_3_fails()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/orders', [
                'package_id' => $this->package->id,
                'quantity' => 150,
                'event_date' => now()->addDays(3)->format('Y-m-d'), // Kurang 2 hari
                'event_name' => 'Pernikahan',
                'event_location' => 'Jakarta',
                'event_address' => 'Jl. Merdeka',
                'delivery_time' => '10:30',
                'contact_name' => 'Ahmad',
                'contact_phone' => '08123456789',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('event_date');
    }

    /**
     * Test pemesanan 50 kotak dengan H+1
     */
    public function test_create_order_with_50_boxes_and_h_plus_1_is_valid()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/orders', [
                'package_id' => $this->package->id,
                'quantity' => 50,
                'event_date' => now()->addDay()->format('Y-m-d'),
                'event_name' => 'Pernikahan',
                'event_location' => 'Jakarta',
                'event_address' => 'Jl. Merdeka',
                'delivery_time' => '10:30',
                'contact_name' => 'Ahmad',
                'contact_phone' => '08123456789',
            ]);

        $response->assertStatus(200);
    }
}
