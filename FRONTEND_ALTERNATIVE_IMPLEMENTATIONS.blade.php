<!-- ALTERNATIVE IMPLEMENTATIONS - Frontend Order Form Validator -->

<!-- ============================================================
IMPLEMENTASI 1: Inline Script (Minimal Setup)
============================================================ -->

@section('alternative1-inline-script')

<!-- Letakkan di dalam form order Blade file -->
<form id="order-form">
    <!-- Input elements -->
    <input type="number" id="quantity" name="quantity" required>
    <input type="date" id="delivery_date" name="delivery_date" required>
    
    <button type="submit">Submit</button>
</form>

<!-- Script inline - tidak perlu import file terpisah -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity');
    const deliveryInput = document.getElementById('delivery_date');

    function updateDeliveryConstraints() {
        const quantity = parseInt(quantityInput.value) || 0;
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Hitung offset hari
        const daysOffset = quantity > 100 ? 5 : 1;
        
        // Hitung min date
        const minDate = new Date(today);
        minDate.setDate(minDate.getDate() + daysOffset);
        
        // Hitung max date
        const maxDate = new Date(today);
        maxDate.setDate(maxDate.getDate() + 90);

        // Set attribute
        const formatDate = (date) => date.toISOString().split('T')[0];
        deliveryInput.min = formatDate(minDate);
        deliveryInput.max = formatDate(maxDate);

        // Reset jika melebihi min
        if (deliveryInput.value < deliveryInput.min) {
            deliveryInput.value = deliveryInput.min;
        }
    }

    // Event listener
    quantityInput.addEventListener('change', updateDeliveryConstraints);
    quantityInput.addEventListener('input', updateDeliveryConstraints);
    
    // Initial set
    updateDeliveryConstraints();
});
</script>

@endsection


<!-- ============================================================
IMPLEMENTASI 2: Alpine.js (Reactive, Modern)
============================================================ -->

@section('alternative2-alpine')

<!-- Alpine.js based implementation -->
<div x-data="orderForm()" class="space-y-4">
    
    <!-- Info Block -->
    <div class="p-3 bg-blue-50 rounded-lg" x-show="showInfo && isLargeOrder">
        <p class="text-sm">
            <strong>⚠️ Pesanan Besar:</strong> 
            Minimum tanggal pengiriman: <span x-text="minDateFormatted"></span> (H+5)
        </p>
    </div>

    <!-- Quantity Input -->
    <div>
        <label>Jumlah Pesanan (Kotak)</label>
        <input type="number" 
               x-model.number="quantity" 
               @change="updateConstraints()"
               min="1" 
               required>
    </div>

    <!-- Delivery Date Input -->
    <div>
        <label>Tanggal Pengiriman</label>
        <input type="date" 
               x-model="deliveryDate"
               :min="minDate"
               :max="maxDate"
               required>
    </div>

</div>

<script>
function orderForm() {
    return {
        quantity: 1,
        deliveryDate: '',
        minDate: '',
        maxDate: '',
        minDateFormatted: '',
        isLargeOrder: false,
        showInfo: false,

        updateConstraints() {
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            const daysOffset = this.quantity > 100 ? 5 : 1;
            this.isLargeOrder = this.quantity > 100;
            this.showInfo = true;

            // Min date
            const minDate = new Date(today);
            minDate.setDate(minDate.getDate() + daysOffset);
            this.minDate = this.formatDate(minDate);
            this.minDateFormatted = minDate.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            // Max date
            const maxDate = new Date(today);
            maxDate.setDate(maxDate.getDate() + 90);
            this.maxDate = this.formatDate(maxDate);

            // Reset delivery date jika melebihi min
            if (this.deliveryDate && this.deliveryDate < this.minDate) {
                this.deliveryDate = this.minDate;
            }
        },

        formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        },

        init() {
            this.updateConstraints();
        }
    }
}
</script>

@endsection


<!-- ============================================================
IMPLEMENTASI 3: Vue.js (Enterprise-grade)
============================================================ -->

@section('alternative3-vue')

<template>
  <div class="order-form" v-if="formReady">
    
    <!-- Alert untuk pesanan besar -->
    <div v-if="isLargeOrder" class="alert alert-warning">
      <strong>⚠️ Pesanan Besar:</strong>
      Pesanan {{ quantity }} kotak membutuhkan minimum H+5.
      Tanggal pengiriman tidak boleh sebelum {{ minDateFormatted }}
    </div>

    <!-- Quantity Input -->
    <div class="form-group">
      <label>Jumlah Pesanan (Kotak)</label>
      <input type="number" 
             v-model.number="quantity" 
             @change="updateConstraints"
             min="1" 
             required>
    </div>

    <!-- Delivery Date Input -->
    <div class="form-group">
      <label>Tanggal Pengiriman</label>
      <input type="date" 
             v-model="deliveryDate"
             :min="minDate"
             :max="maxDate"
             required>
      <small v-if="constraintMessage">{{ constraintMessage }}</small>
    </div>

  </div>
</template>

<script>
export default {
  data() {
    return {
      quantity: 1,
      deliveryDate: '',
      minDate: '',
      maxDate: '',
      minDateFormatted: '',
      isLargeOrder: false,
      formReady: false
    }
  },

  computed: {
    constraintMessage() {
      const daysOffset = this.isLargeOrder ? 5 : 1;
      return `Tanggal pengiriman minimal H+${daysOffset}`;
    }
  },

  methods: {
    updateConstraints() {
      const today = new Date();
      today.setHours(0, 0, 0, 0);

      const daysOffset = this.quantity > 100 ? 5 : 1;
      this.isLargeOrder = this.quantity > 100;

      // Min date
      const minDate = new Date(today);
      minDate.setDate(minDate.getDate() + daysOffset);
      this.minDate = this.formatDate(minDate);
      this.minDateFormatted = minDate.toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });

      // Max date
      const maxDate = new Date(today);
      maxDate.setDate(maxDate.getDate() + 90);
      this.maxDate = this.formatDate(maxDate);

      // Reset delivery date
      if (this.deliveryDate && this.deliveryDate < this.minDate) {
        this.deliveryDate = this.minDate;
      }
    },

    formatDate(date) {
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const day = String(date.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`;
    }
  },

  mounted() {
    this.updateConstraints();
    this.formReady = true;
  }
}
</script>

@endsection


<!-- ============================================================
IMPLEMENTASI 4: React (JSX)
============================================================ -->

@section('alternative4-react')

import React, { useState, useEffect } from 'react';

export function OrderForm() {
  const [quantity, setQuantity] = useState(1);
  const [deliveryDate, setDeliveryDate] = useState('');
  const [minDate, setMinDate] = useState('');
  const [maxDate, setMaxDate] = useState('');
  const [isLargeOrder, setIsLargeOrder] = useState(false);

  useEffect(() => {
    updateConstraints();
  }, [quantity]);

  const formatDate = (date) => {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
  };

  const updateConstraints = () => {
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    const daysOffset = quantity > 100 ? 5 : 1;
    setIsLargeOrder(quantity > 100);

    // Calculate min date
    const min = new Date(today);
    min.setDate(min.getDate() + daysOffset);
    setMinDate(formatDate(min));

    // Calculate max date
    const max = new Date(today);
    max.setDate(max.getDate() + 90);
    setMaxDate(formatDate(max));

    // Reset delivery date if needed
    if (deliveryDate && deliveryDate < formatDate(min)) {
      setDeliveryDate(formatDate(min));
    }
  };

  return (
    <div>
      {isLargeOrder && (
        <div className="alert alert-warning">
          <strong>⚠️ Pesanan Besar:</strong> 
          Minimum tanggal pengiriman: {minDate} (H+5)
        </div>
      )}

      <div>
        <label>Jumlah Pesanan (Kotak)</label>
        <input
          type="number"
          value={quantity}
          onChange={(e) => setQuantity(parseInt(e.target.value) || 1)}
          min="1"
          required
        />
      </div>

      <div>
        <label>Tanggal Pengiriman</label>
        <input
          type="date"
          value={deliveryDate}
          onChange={(e) => setDeliveryDate(e.target.value)}
          min={minDate}
          max={maxDate}
          required
        />
      </div>
    </div>
  );
}

@endsection


<!-- ============================================================
IMPLEMENTASI 5: Vanilla JS dengan CSS Classes (Styling)
============================================================ -->

@section('alternative5-styled')

<!-- HTML Structure -->
<div class="order-form-wrapper" id="orderFormWrapper">
    <div class="constraint-info constraint-info--hidden" id="constraintInfo">
        <div class="constraint-info__content">
            <span class="constraint-info__icon">📋</span>
            <div>
                <p class="constraint-info__title">Aturan Pengiriman</p>
                <p class="constraint-info__text" id="constraintText"></p>
            </div>
        </div>
    </div>

    <form id="orderForm" class="order-form">
        <div class="form-group">
            <label for="quantity" class="form-label">Jumlah Pesanan (Kotak)</label>
            <input 
                type="number" 
                id="quantity" 
                name="quantity"
                class="form-control"
                min="1"
                value="1"
                required
            >
        </div>

        <div class="form-group">
            <label for="delivery_date" class="form-label">Tanggal Pengiriman</label>
            <input 
                type="date" 
                id="delivery_date" 
                name="delivery_date"
                class="form-control"
                required
            >
            <small class="form-text" id="deliveryHint"></small>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<!-- Styles -->
<style>
.constraint-info {
    padding: 1rem;
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
    background: #f3f4f6;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
}

.constraint-info--hidden {
    display: none !important;
}

.constraint-info--warning {
    background: #fef3c7;
    border-color: #fcd34d;
}

.constraint-info__icon {
    font-size: 1.25rem;
}

.constraint-info__title {
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.constraint-info__text {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0.25rem 0 0 0;
}

.form-group {
    margin-bottom: 1rem;
}

.form-label {
    display: block;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.form-control {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 1rem;
}

.form-control:focus {
    outline: none;
    border-color: #f97316;
    box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
}

.form-text {
    display: block;
    font-size: 0.875rem;
    color: #9ca3af;
    margin-top: 0.25rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 0.375rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background: #f97316;
    color: white;
}

.btn-primary:hover {
    background: #ea580c;
}
</style>

<!-- JavaScript -->
<script>
class StyledOrderFormValidator {
    constructor() {
        this.quantityInput = document.getElementById('quantity');
        this.deliveryInput = document.getElementById('delivery_date');
        this.constraintInfo = document.getElementById('constraintInfo');
        this.constraintText = document.getElementById('constraintText');
        this.deliveryHint = document.getElementById('deliveryHint');
        
        this.init();
    }

    init() {
        this.quantityInput.addEventListener('change', () => this.update());
        this.quantityInput.addEventListener('input', () => this.update());
        this.update();
    }

    update() {
        const quantity = parseInt(this.quantityInput.value) || 1;
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const daysOffset = quantity > 100 ? 5 : 1;
        const isLarge = quantity > 100;

        // Hitung dates
        const minDate = new Date(today);
        minDate.setDate(minDate.getDate() + daysOffset);

        const maxDate = new Date(today);
        maxDate.setDate(maxDate.getDate() + 90);

        // Format dates
        const minDateStr = this.formatDate(minDate);
        const maxDateStr = this.formatDate(maxDate);
        const minDateReadable = minDate.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        // Update input constraints
        this.deliveryInput.min = minDateStr;
        this.deliveryInput.max = maxDateStr;

        if (this.deliveryInput.value < minDateStr) {
            this.deliveryInput.value = minDateStr;
        }

        // Update UI
        this.updateConstraintInfo(quantity, daysOffset, minDateReadable, isLarge);
        this.updateDeliveryHint(daysOffset, minDateReadable);
    }

    updateConstraintInfo(quantity, offset, minDate, isLarge) {
        const text = isLarge
            ? `Pesanan ${quantity} kotak membutuhkan H+${offset}. Min: ${minDate}`
            : `Pesanan ${quantity} kotak dapat dikirim H+${offset}. Min: ${minDate}`;

        this.constraintText.textContent = text;

        if (isLarge) {
            this.constraintInfo.classList.add('constraint-info--warning');
            this.constraintInfo.classList.remove('constraint-info--hidden');
        } else {
            this.constraintInfo.classList.remove('constraint-info--warning');
            this.constraintInfo.classList.add('constraint-info--hidden');
        }
    }

    updateDeliveryHint(offset, minDate) {
        this.deliveryHint.textContent = `Tanggal minimal: ${minDate} (H+${offset})`;
    }

    formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    new StyledOrderFormValidator();
});
</script>

@endsection
