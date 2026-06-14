# 🚀 QUICK START - Custom Validation Rule Delivery Date

## File yang Sudah Dibuat

✅ `app/Rules/ValidateDeliveryDateByQuantity.php` - Custom Rule  
✅ `app/Http/Requests/CreateOrderRequest.php` - Form Request  
✅ `CUSTOM_VALIDATION_RULE_GUIDE.md` - Dokumentasi Lengkap  
✅ `CUSTOM_VALIDATION_ALTERNATIVE_USAGE.php` - Alternatif Penggunaan  

---

## ⚡ Implementasi Cepat (3 Langkah)

### Langkah 1: Update Controller

```php
<?php
namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Models\Order;

class OrderController extends Controller
{
    public function store(CreateOrderRequest $request)
    {
        // Validasi sudah otomatis ✅
        $validated = $request->validated();

        // Simpan order
        $order = Order::create($validated);

        return response()->json([
            'success' => true,
            'order_id' => $order->id,
        ]);
    }
}
```

### Langkah 2: Update Route

```php
// routes/web.php
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store')->middleware('auth');
```

### Langkah 3: Kirim Request dari Frontend

```javascript
const formData = {
    package_id: 1,
    quantity: 150,           // > 100 kotak
    event_date: '2026-06-20', // Harus >= H+5 (19 Juni)
    event_name: 'Pernikahan',
    event_location: 'Jakarta',
    event_address: 'Jl. Merdeka No. 123',
    delivery_time: '10:30',
    contact_name: 'Ahmad',
    contact_phone: '08123456789'
};

fetch('/orders', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify(formData)
})
.then(r => r.json())
.then(data => console.log(data));
```

---

## 📋 Aturan Validasi Ringkas

| Kondisi | Minimal Delivery Date | Contoh |
|---------|----------------------|---------|
| Quantity ≤ 100 | H+1 | 14 Juni → 15 Juni ✅ |
| Quantity > 100 | H+5 | 14 Juni → 19 Juni ✅ |
| Max | H+90 | Tidak boleh > 90 hari |

---

## ❌ Pesan Error Contoh

### Valid Request
- Quantity: 150 kotak
- Hari ini: 14 Juni 2026
- Minimum delivery: 19 Juni 2026 (H+5)
- Request date: 20 Juni 2026
- **Status: ✅ VALID**

### Invalid Request
- Quantity: 150 kotak
- Hari ini: 14 Juni 2026
- Minimum delivery: 19 Juni 2026 (H+5)
- Request date: 17 Juni 2026
- **Status: ❌ INVALID**
- **Error**: "Untuk pesanan lebih dari 100 kotak, tanggal pengiriman minimal harus 19 June 2026 (H+5)."

---

## 🔧 Setup Custom Field (Jika Perlu)

Jika Anda ingin menggunakan nama field yang berbeda (mis. `delivery_date` bukan `event_date`), edit `CreateOrderRequest.php`:

```php
public function rules(): array
{
    $quantity = (int) $this->input('quantity', 0);

    return [
        // Ganti 'event_date' menjadi 'delivery_date'
        'delivery_date' => [
            'required', 
            'date_format:Y-m-d',
            new ValidateDeliveryDateByQuantity($quantity)
        ],
    ];
}
```

---

## 📝 Testing Manual dengan Postman/Insomnia

### Setup
1. Copy URL: `http://localhost:8000/orders` atau URL app Anda
2. Method: `POST`
3. Headers: `Content-Type: application/json`
4. Tambahkan CSRF token jika needed

### Test Case 1: Valid (50 kotak, H+1)
```json
{
  "package_id": 1,
  "quantity": 50,
  "event_date": "2026-06-15",
  "event_name": "Test Event",
  "event_location": "Jakarta",
  "event_address": "Jl. Test",
  "delivery_time": "10:00",
  "contact_name": "Test",
  "contact_phone": "08123456789"
}
```
**Expected**: `200 OK` ✅

### Test Case 2: Invalid (150 kotak, H+2)
```json
{
  "package_id": 1,
  "quantity": 150,
  "event_date": "2026-06-16",
  "event_name": "Test Event",
  "event_location": "Jakarta",
  "event_address": "Jl. Test",
  "delivery_time": "10:00",
  "contact_name": "Test",
  "contact_phone": "08123456789"
}
```
**Expected**: `422 Unprocessable Entity` dengan error event_date ❌

---

## 🐛 Troubleshooting

### Error: Class Not Found
```
Error: Class 'App\Rules\ValidateDeliveryDateByQuantity' not found
```
**Solusi**: Pastikan file `app/Rules/ValidateDeliveryDateByQuantity.php` ada dan namespace benar.

### Validasi Tidak Berjalan
```
Pesanan bisa disimpan meskipun tanggal tidak sesuai aturan
```
**Solusi**: Pastikan controller menggunakan `CreateOrderRequest`:
```php
// ✅ BENAR
public function store(CreateOrderRequest $request)

// ❌ SALAH
public function store(Request $request)
```

### Pesan Error Dalam Bahasa Inggris
**Solusi**: Pastikan locale di `config/app.php` adalah `'locale' => 'id'` untuk format tanggal.

---

## 📚 Dokumentasi Lengkap

Untuk detail lebih lanjut, baca:
- `CUSTOM_VALIDATION_RULE_GUIDE.md` - Dokumentasi komprehensif
- `CUSTOM_VALIDATION_ALTERNATIVE_USAGE.php` - Alternatif implementasi

---

## ✨ Fitur Lengkap Custom Rule

✅ Validasi quantity > 100 = minimum H+5  
✅ Validasi quantity ≤ 100 = minimum H+1  
✅ Validasi maksimal H+90  
✅ Pesan error informatif dalam bahasa Indonesia  
✅ Support berbagai format tanggal  
✅ Mudah diperluas untuk validasi tambahan  

---

## 🎯 Next Steps

1. Test dengan Postman
2. Integrasikan ke form order di frontend
3. Tambahkan client-side validation (opsional tapi recommended)
4. Setup testing (lihat CUSTOM_VALIDATION_ALTERNATIVE_USAGE.php)
5. Deploy ke production

---

Selamat! Custom Validation Rule Anda sudah siap digunakan! 🚀
