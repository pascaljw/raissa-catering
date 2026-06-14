/**
 * Order Form Validation Handler
 * Menangani validasi interaktif untuk form pemesanan catering
 * 
 * Fitur:
 * - Update minimum delivery_date berdasarkan quantity
 * - Quantity > 100: minimum H+5
 * - Quantity ≤ 100: minimum H+1
 */

class OrderFormValidator {
    constructor(options = {}) {
        this.quantityInput = document.getElementById(options.quantitySelector || 'quantity');
        this.deliveryDateInput = document.getElementById(options.deliveryDateSelector || 'delivery_date');
        this.minQuantityThreshold = options.minQuantityThreshold || 100;
        this.standardDaysOffset = options.standardDaysOffset || 1;  // H+1
        this.largeDaysOffset = options.largeDaysOffset || 5;        // H+5
        this.maxDaysOffset = options.maxDaysOffset || 90;           // H+90
        
        if (this.quantityInput && this.deliveryDateInput) {
            this.init();
        }
    }

    /**
     * Initialize event listeners
     */
    init() {
        // Listen ke perubahan quantity
        this.quantityInput.addEventListener('change', () => this.updateDeliveryDateConstraints());
        this.quantityInput.addEventListener('input', () => this.updateDeliveryDateConstraints());
        
        // Set constraint saat halaman load
        this.updateDeliveryDateConstraints();
        
        // Debug info
        console.log('✅ OrderFormValidator initialized');
    }

    /**
     * Update minimum dan maksimum tanggal berdasarkan quantity
     */
    updateDeliveryDateConstraints() {
        const quantity = this.getQuantity();
        const minDate = this.calculateMinDate(quantity);
        const maxDate = this.calculateMaxDate();

        // Set attribute min dan max pada input date
        this.deliveryDateInput.min = minDate;
        this.deliveryDateInput.max = maxDate;

        // Reset nilai delivery_date jika sudah melebihi min
        if (this.deliveryDateInput.value && this.deliveryDateInput.value < minDate) {
            this.deliveryDateInput.value = minDate;
        }

        // Emit event custom (untuk UI feedback)
        this.emitConstraintChangedEvent(quantity, minDate, maxDate);

        console.log(`📅 Constraints updated | Quantity: ${quantity} | Min: ${minDate} | Max: ${maxDate}`);
    }

    /**
     * Hitung minimum delivery date berdasarkan quantity
     * @param {number} quantity 
     * @returns {string} tanggal dalam format YYYY-MM-DD
     */
    calculateMinDate(quantity) {
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Tentukan jumlah hari offset
        const daysOffset = quantity >= this.minQuantityThreshold 
            ? this.largeDaysOffset 
            : this.standardDaysOffset;

        // Hitung minimum date
        const minDate = new Date(today);
        minDate.setDate(minDate.getDate() + daysOffset);

        return this.formatDateForInput(minDate);
    }

    /**
     * Hitung maksimum delivery date (H+90)
     * @returns {string} tanggal dalam format YYYY-MM-DD
     */
    calculateMaxDate() {
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const maxDate = new Date(today);
        maxDate.setDate(maxDate.getDate() + this.maxDaysOffset);

        return this.formatDateForInput(maxDate);
    }

    /**
     * Get quantity value dari input
     * @returns {number}
     */
    getQuantity() {
        return parseInt(this.quantityInput.value) || 0;
    }

    /**
     * Format date ke format YYYY-MM-DD untuk input type="date"
     * @param {Date} date 
     * @returns {string}
     */
    formatDateForInput(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    /**
     * Emit custom event saat constraint berubah
     * Berguna untuk update UI feedback
     */
    emitConstraintChangedEvent(quantity, minDate, maxDate) {
        const event = new CustomEvent('constraintChanged', {
            detail: {
                quantity: quantity,
                minDate: minDate,
                maxDate: maxDate,
                isLargeOrder: quantity >= this.minQuantityThreshold,
                daysOffset: quantity >= this.minQuantityThreshold 
                    ? this.largeDaysOffset 
                    : this.standardDaysOffset
            }
        });

        this.deliveryDateInput.dispatchEvent(event);
    }

    /**
     * Get info tentang current constraint
     * Berguna untuk menampilkan info ke user
     */
    getConstraintInfo() {
        const quantity = this.getQuantity();
        const minDate = this.calculateMinDate(quantity);
        const isLargeOrder = quantity >= this.minQuantityThreshold;
        const daysOffset = isLargeOrder ? this.largeDaysOffset : this.standardDaysOffset;

        return {
            quantity: quantity,
            isLargeOrder: isLargeOrder,
            minDate: minDate,
            daysOffset: daysOffset,
            message: this.generateConstraintMessage(quantity, minDate, daysOffset)
        };
    }

    /**
     * Generate pesan informatif tentang constraint
     */
    generateConstraintMessage(quantity, minDate, daysOffset) {
        const dateObj = new Date(minDate + 'T00:00:00');
        const formattedDate = dateObj.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        if (quantity >= this.minQuantityThreshold) {
            return `Pesanan besar (≥${this.minQuantityThreshold} kotak) membutuhkan minimal H+${daysOffset}. Tanggal pengiriman tidak boleh sebelum ${formattedDate}.`;
        } else {
            return `Pesanan standar (<${this.minQuantityThreshold} kotak) dapat dijadwalkan mulai H+${daysOffset}. Tanggal pengiriman tidak boleh sebelum ${formattedDate}.`;
        }
    }

    /**
     * Validasi manual (berguna jika perlu custom validation)
     */
    validate() {
        const quantity = this.getQuantity();
        const deliveryDate = this.deliveryDateInput.value;
        const minDate = this.calculateMinDate(quantity);

        if (!deliveryDate) {
            return {
                valid: false,
                message: 'Tanggal pengiriman harus diisi.'
            };
        }

        if (deliveryDate < minDate) {
            const info = this.getConstraintInfo();
            return {
                valid: false,
                message: info.message
            };
        }

        return {
            valid: true,
            message: 'Validasi berhasil.'
        };
    }
}

// Make class available on window for inline Blade scripts
if (typeof window !== 'undefined') {
    window.OrderFormValidator = OrderFormValidator;
}

// Export untuk penggunaan di modul lain jika perlu
if (typeof module !== 'undefined' && module.exports) {
    module.exports = OrderFormValidator;
}
