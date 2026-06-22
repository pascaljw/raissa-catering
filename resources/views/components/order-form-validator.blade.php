@props(['quantitySelector' => 'quantity', 'deliveryDateSelector' => 'delivery_date'])

{{-- Component untuk OrderFormValidator dengan UI feedback --}}

<div class="order-form-validator-wrapper" data-quantity-selector="{{ $quantitySelector }}" data-delivery-selector="{{ $deliveryDateSelector }}">
    
    {{-- Alert informatif - ditampilkan saat constraint berubah --}}
    <div id="constraint-info-alert" class="hidden mb-4 p-3 rounded-lg bg-blue-50 border border-blue-200 text-blue-800 text-sm">
        <div class="flex items-start gap-2">
            <span class="text-lg mt-0.5">ℹ️</span>
            <div id="constraint-info-text" class="leading-relaxed"></div>
        </div>
    </div>

    {{-- Daftar peraturan constraint --}}
    <div class="mb-4 p-3 rounded-lg bg-gray-50 border border-gray-200">
        <p class="text-xs font-semibold text-gray-700 mb-2">📋 Aturan Tanggal Pengiriman:</p>
        <ul class="text-xs text-gray-600 space-y-1">
            <li>✅ Pesanan kurang dari 100 kotak: Minimum H+1 (besok)</li>
            <li>✅ Pesanan 100 kotak atau lebih: Minimum H+5 (5 hari kemudian)</li>
            <li>✅ Maksimal: H+90 (90 hari ke depan)</li>
        </ul>
    </div>

    {{-- Informasi real-time - update saat quantity berubah --}}
    <div id="live-constraint-info" class="mb-4 p-3 rounded-lg bg-orange-50/70 border border-orange-100">
        <p class="text-sm text-gray-700">
            <span class="font-semibold">Jumlah pesanan Anda:</span> 
            <span id="live-quantity" class="font-bold text-orange-600">-</span> kotak
        </p>
        <p class="text-sm text-gray-700 mt-1">
            <span class="font-semibold">Tanggal pengiriman minimal:</span> 
            <span id="live-min-date" class="font-bold text-orange-600">-</span>
            <span id="live-days-offset" class="text-xs text-gray-500">-</span>
        </p>
    </div>

</div>

<script>
    /**
     * Initialize Order Form Validator
     */
    document.addEventListener('DOMContentLoaded', function() {
        const wrapper = document.querySelector('.order-form-validator-wrapper');
        if (!wrapper) return;

        const quantitySelector = wrapper.dataset.quantitySelector || 'quantity';
        const deliveryDateSelector = wrapper.dataset.deliverySelector || 'delivery_date';
        const quantityInput = document.getElementById(quantitySelector);
        const deliveryDateInput = document.getElementById(deliveryDateSelector);

        if (!quantityInput || !deliveryDateInput) {
            console.warn('OrderFormValidator: Input quantity atau delivery_date tidak ditemukan.');
            return;
        }

        function initValidator() {
            if (typeof window.OrderFormValidator === 'function') {
                const validator = new window.OrderFormValidator({
                    quantitySelector: quantitySelector,
                    deliveryDateSelector: deliveryDateSelector,
                    minQuantityThreshold: 100,
                    standardDaysOffset: 1,
                    largeDaysOffset: 5,
                    maxDaysOffset: 90
                });

                attachUiListeners(validator, deliveryDateInput);
                const initialInfo = validator.getConstraintInfo();
                updateConstraintUI(initialInfo);
                window.orderFormValidator = validator;
                console.log('✅ Order Form Validator UI initialized');
                return true;
            }
            return false;
        }

        function attachUiListeners(validator, deliveryDateInput) {
            deliveryDateInput.addEventListener('constraintChanged', function(e) {
                updateConstraintUI(e.detail);
            });
        }

        function updateConstraintUI(constraintData) {
            document.getElementById('live-quantity').textContent = constraintData.quantity;
            const minDateObj = new Date(constraintData.minDate + 'T00:00:00');
            const formattedMinDate = minDateObj.toLocaleDateString('id-ID', {
                weekday: 'short',
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
            document.getElementById('live-min-date').textContent = formattedMinDate;
            const daysText = constraintData.isLargeOrder ? 'H+5' : 'H+1';
            document.getElementById('live-days-offset').textContent = `(${daysText})`;
            showConstraintAlert(constraintData);
        }

        function showConstraintAlert(constraintData) {
            const alertDiv = document.getElementById('constraint-info-alert');
            const infoText = document.getElementById('constraint-info-text');

            if (constraintData.isLargeOrder) {
                const minDateObj = new Date(constraintData.minDate + 'T00:00:00');
                const formattedDate = minDateObj.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                infoText.innerHTML = `
                    <strong>⚠️ Pesanan Besar Terdeteksi:</strong> 
                    Pesanan Anda ${constraintData.quantity} kotak membutuhkan persiapan lebih. 
                    Tanggal pengiriman minimal: <strong>${formattedDate}</strong> (H+5)
                `;
                alertDiv.classList.remove('hidden');
            } else {
                alertDiv.classList.add('hidden');
            }
        }

        function fallbackInit() {
            function formatDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            function calculateMinDate(quantity) {
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const offset = quantity >= 100 ? 5 : 1;
                const minDate = new Date(today);
                minDate.setDate(minDate.getDate() + offset);
                return formatDate(minDate);
            }

            function calculateMaxDate() {
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const maxDate = new Date(today);
                maxDate.setDate(maxDate.getDate() + 90);
                return formatDate(maxDate);
            }

            function updateConstraints() {
                const quantity = parseInt(quantityInput.value, 10) || 0;
                const minDate = calculateMinDate(quantity);
                const maxDate = calculateMaxDate();
                deliveryDateInput.min = minDate;
                deliveryDateInput.max = maxDate;
                if (deliveryDateInput.value && deliveryDateInput.value < minDate) {
                    deliveryDateInput.value = minDate;
                }
                const constraintData = {
                    quantity: quantity,
                    minDate: minDate,
                    isLargeOrder: quantity >= 100,
                };
                updateConstraintUI(constraintData);
            }

            quantityInput.addEventListener('input', updateConstraints);
            quantityInput.addEventListener('change', updateConstraints);
            updateConstraints();
            console.log('✅ OrderFormValidator fallback initialized');
        }

        if (!initValidator()) {
            // If the module script hasn't loaded yet, retry a few times
            let retries = 0;
            const retryInit = setInterval(() => {
                if (initValidator() || retries >= 10) {
                    clearInterval(retryInit);
                    if (retries >= 10) {
                        console.warn('OrderFormValidator: module not loaded, using fallback init.');
                        fallbackInit();
                    }
                }
                retries += 1;
            }, 100);
        }
    });
</script>
