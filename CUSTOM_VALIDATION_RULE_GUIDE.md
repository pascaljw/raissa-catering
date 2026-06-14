# Custom Validation Rule: Validasi Delivery Date Berdasarkan Quantity

## 📋 Daftar Isi
1. [Pengenalan](#pengenalan)
2. [Struktur File](#struktur-file)
3. [Aturan Bisnis](#aturan-bisnis)
4. [Cara Penggunaan](#cara-penggunaan)
5. [Contoh Request](#contoh-request)
6. [Testing](#testing)
7. [Pesan Error](#pesan-error)

---

## Pengenalan

Custom validation rule `ValidateDeliveryDateByQuantity` dibuat untuk memvalidasi tanggal pengiriman (delivery_date) berdasarkan jumlah pesanan (quantity) dengan aturan bisnis khusus.

### File yang dibuat:
- `app/Rules/ValidateDeliveryDateByQuantity.php` - Custom Validation Rule
- `app/Http/Requests/CreateOrderRequest.php` - Form Request
- `app/Http/Controllers/OrderController_CONTOH.php` - Contoh implementasi

---

## Struktur File

### 1. Custom Validation Rule (`app/Rules/ValidateDeliveryDateByQuantity.php`)

```
App
└── Rules
    └── ValidateDeliveryDateByQuantity.php
```

**Fitur:**
- Menerima quantity sebagai parameter di constructor
- Mengecek apakah delivery_date memenuhi syarat berdasarkan quantity
- Memberikan pesan error yang informatif dalam bahasa Indonesia
- Menggunakan Carbon untuk manipulasi tanggal

### 2. Form Request (`app/Http/Requests/CreateOrderRequest.php`)

```
App
└── Http
    └── Requests
        └── CreateOrderRequest.php
```

**Fitur:**
- Mengintegrasikan custom rule ke dalam validasi
- Validasi semua field pemesanan
- Pesan error lengkap dalam bahasa Indonesia

---

## Aturan Bisnis

### 1. Validasi Quantity
- Minimal: 1 kotak
- Maksimal: 500 kotak

### 2. Validasi Delivery Date Berdasarkan Quantity

| Kondisi | Minimum Delivery Date | Deskripsi |
|---------|----------------------|-----------|
| Quantity ≤ 100 kotak | H+1 (1 hari dari hari ini) | Pesanan kecil, pengiriman cepat |
| Quantity > 100 kotak | H+5 (5 hari dari hari ini) | Pesanan besar, butuh persiapan |
| Maksimal | H+90 (90 hari ke depan) | Tidak boleh terlalu jauh |

### 3. Contoh Skenario

**Skenario A: Pesanan 50 kotak**
- Hari pemesanan: 14 Juni 2026
- Minimum delivery date: 15 Juni 2026 (H+1)
- ✅ Valid jika tanggal pengiriman ≥ 15 Juni 2026

**Skenario B: Pesanan 150 kotak**
- Hari pemesanan: 14 Juni 2026
- Minimum delivery date: 19 Juni 2026 (H+5)
- ✅ Valid jika tanggal pengiriman ≥ 19 Juni 2026
- ❌ Invalid jika tanggal pengiriman < 19 Juni 2026

---

## Cara Penggunaan

### Langkah 1: Gunakan Form Request di Controller

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Models\Order;

class OrderController extends Controller
{
    public function store(CreateOrderRequest $request)
    {
        // Validasi sudah dilakukan otomatis
        // Data yang sudah tervalidasi bisa diakses
        $validated = $request->validated();

        // Lanjutkan proses penyimpanan order
        $order = Order::create([
            'package_id'      => $validated['package_id'],
            'quantity'        => $validated['quantity'],
            'event_date'      => $validated['event_date'],
            'event_name'      => $validated['event_name'],
            // ... field lainnya
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat.',
            'order_id' => $order->id,
        ]);
    }
}
```

### Langkah 2: Kirim Request dari Frontend

```javascript
// Contoh menggunakan JavaScript/AJAX
fetch('/orders', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({
        package_id: 1,
        quantity: 120,
        event_date: '2026-06-20', // Harus H+5 atau lebih
        event_name: 'Pernikahan Ahmad & Siti',
        event_location: 'Gedung Pesona Indah',
        event_address: 'Jl. Merdeka No. 123, Jakarta',
        delivery_time: '10:30',
        contact_name: 'Ahmad',
        contact_phone: '08123456789',
        selected_addons: [1, 2], // Optional
        notes: 'Mohon tepat waktu' // Optional
    })
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        console.log('Pesanan berhasil:', data);
    } else {
        console.error('Error:', data.message);
    }
})
.catch(error => console.error('Error:', error));
```

### Langkah 3: Handle Error Response di Frontend

```javascript
// Jika ada validation error, response akan seperti ini:
{
    "message": "The given data was invalid.",
    "errors": {
        "event_date": [
            "Untuk pesanan lebih dari 100 kotak, tanggal pengiriman minimal harus 20 June 2026 (H+5)."
        ],
        "quantity": [
            "Jumlah pesanan harus berupa angka bulat."
        ]
    }
}
```

---

## Contoh Request

### Request Valid (Pesanan 150 kotak dengan H+5)

```json
{
    "package_id": 1,
    "quantity": 150,
    "event_date": "2026-06-20",
    "event_name": "Pernikahan Ahmad & Siti",
    "event_location": "Gedung Pesona Indah",
    "event_address": "Jl. Merdeka No. 123, Jakarta Pusat",
    "delivery_time": "10:30",
    "contact_name": "Ahmad",
    "contact_phone": "+62812345678",
    "selected_addons": [1, 2],
    "notes": "Mohon tepat waktu"
}
```

**Catatan:**
- Hari pemesanan: 14 Juni 2026 (Sabtu)
- H+5: 19 Juni 2026 (Kamis)
- Tanggal pengiriman: 20 Juni 2026 ✅ VALID (lebih dari H+5)

### Request Invalid (Pesanan 150 kotak dengan H+3)

```json
{
    "package_id": 1,
    "quantity": 150,
    "event_date": "2026-06-17",
    "event_name": "Pernikahan Ahmad & Siti",
    "event_location": "Gedung Pesona Indah",
    "event_address": "Jl. Merdeka No. 123, Jakarta Pusat",
    "delivery_time": "10:30",
    "contact_name": "Ahmad",
    "contact_phone": "+62812345678"
}
```

**Error Response:**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "event_date": [
            "Untuk pesanan lebih dari 100 kotak, tanggal pengiriman minimal harus 19 June 2026 (H+5)."
        ]
    }
}
```

---

## Testing

### Unit Test untuk Custom Rule

```php
<?php

namespace Tests\Unit\Rules;

use App\Rules\ValidateDeliveryDateByQuantity;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class ValidateDeliveryDateByQuantityTest extends TestCase
{
    /**
     * Test: Pesanan ≤100 kotak dengan H+1 (VALID)
     */
    public function test_quantity_100_or_less_with_h_plus_1_is_valid()
    {
        $rule = new ValidateDeliveryDateByQuantity(50);
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        $valid = true;
        $rule->validate('event_date', $tomorrow, fn() => $valid = false);

        $this->assertTrue($valid);
    }

    /**
     * Test: Pesanan ≤100 kotak dengan hari ini (INVALID)
     */
    public function test_quantity_100_or_less_with_today_is_invalid()
    {
        $rule = new ValidateDeliveryDateByQuantity(100);
        $today = Carbon::today()->format('Y-m-d');

        $valid = true;
        $rule->validate('event_date', $today, fn() => $valid = false);

        $this->assertFalse($valid);
    }

    /**
     * Test: Pesanan >100 kotak dengan H+5 (VALID)
     */
    public function test_quantity_more_than_100_with_h_plus_5_is_valid()
    {
        $rule = new ValidateDeliveryDateByQuantity(150);
        $h_plus_5 = Carbon::today()->addDays(5)->format('Y-m-d');

        $valid = true;
        $rule->validate('event_date', $h_plus_5, fn() => $valid = false);

        $this->assertTrue($valid);
    }

    /**
     * Test: Pesanan >100 kotak dengan H+3 (INVALID)
     */
    public function test_quantity_more_than_100_with_h_plus_3_is_invalid()
    {
        $rule = new ValidateDeliveryDateByQuantity(200);
        $h_plus_3 = Carbon::today()->addDays(3)->format('Y-m-d');

        $valid = true;
        $rule->validate('event_date', $h_plus_3, fn() => $valid = false);

        $this->assertFalse($valid);
    }

    /**
     * Test: Tanggal pengiriman >90 hari ke depan (INVALID)
     */
    public function test_delivery_date_more_than_90_days_is_invalid()
    {
        $rule = new ValidateDeliveryDateByQuantity(50);
        $h_plus_100 = Carbon::today()->addDays(100)->format('Y-m-d');

        $valid = true;
        $rule->validate('event_date', $h_plus_100, fn() => $valid = false);

        $this->assertFalse($valid);
    }
}
```

---

## Pesan Error

### Pesan Error dalam Bahasa Indonesia

| Field | Kondisi | Pesan Error |
|-------|---------|------------|
| `event_date` | Quantity > 100 & tanggal < H+5 | "Untuk pesanan lebih dari 100 kotak, tanggal pengiriman minimal harus [TGL] (H+5)." |
| `event_date` | Quantity ≤ 100 & tanggal < H+1 | "Tanggal pengiriman minimal harus [TGL] (H+1)." |
| `event_date` | Tanggal > H+90 | "Tanggal pengiriman tidak boleh lebih dari 90 hari ke depan." |
| `quantity` | Tidak diisi | "Jumlah pesanan (kotak) harus diisi." |
| `quantity` | Bukan angka | "Jumlah pesanan harus berupa angka bulat." |
| `quantity` | < 1 | "Minimal pesanan adalah 1 kotak." |
| `quantity` | > 500 | "Maksimal pesanan adalah 500 kotak." |
| `contact_phone` | Format salah | "Nomor telepon harus valid (dimulai dari +62, 62, atau 0)." |

---

## Integrasi dengan Route

### Tambahkan di `routes/web.php`

```php
Route::middleware(['auth'])->group(function () {
    // Route untuk membuat order
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
});
```

---

## Catatan Penting

1. **Field Tanggal**: Gunakan `event_date` di form request. Jika Anda ingin menggunakan nama field yang berbeda, sesuaikan di `CreateOrderRequest`.

2. **Format Tanggal**: Input harus dalam format `Y-m-d` (contoh: `2026-06-20`).

3. **Format Waktu**: Input waktu harus dalam format `H:i` (contoh: `10:30`).

4. **Nomor Telepon**: Harus dimulai dari `+62`, `62`, atau `0` diikuti 9-12 digit.

5. **Carbon DateTime**: Rule ini menggunakan Carbon untuk manipulasi tanggal, pastikan sudah ter-import:
   ```php
   use Carbon\Carbon;
   ```

6. **Timezone**: Pastikan aplikasi Anda sudah set timezone yang benar di `config/app.php`:
   ```php
   'timezone' => 'Asia/Jakarta',
   ```

---

## Troubleshooting

### Error: "Class not found: ValidateDeliveryDateByQuantity"
- Pastikan file `app/Rules/ValidateDeliveryDateByQuantity.php` sudah dibuat
- Pastikan namespace import di `CreateOrderRequest.php` benar:
  ```php
  use App\Rules\ValidateDeliveryDateByQuantity;
  ```

### Error: "The given data was invalid" tanpa detail error
- Pastikan Anda menangani response error dengan benar
- Di Postman/Insomnia, periksa tab "Body" untuk melihat detail error
- Di browser, periksa response JSON di Network tab

### Validasi tidak berjalan
- Pastikan Controller menggunakan `CreateOrderRequest`:
  ```php
  public function store(CreateOrderRequest $request)
  ```
- Pastikan CSRF token disertakan di request (untuk form biasa)

---

## Lihat Juga

- [Laravel Validation Rules](https://laravel.com/docs/11.x/validation#available-validation-rules)
- [Laravel Custom Validation Rule](https://laravel.com/docs/11.x/validation#custom-rules)
- [Carbon Documentation](https://carbon.nesbot.com/)
