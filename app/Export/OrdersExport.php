<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnFormatting, ShouldAutoSize
{
    /**
     * Ambil data pesanan katering dari database
     */
    public function collection()
    {
        return Order::with(['package', 'user'])->latest()->get();
    }

    /**
     * Judul kolom di Excel
     */
    public function headings(): array
    {
        return [
            'ID Order',
            'Tanggal Acara',
            'Nama Pelanggan',
            'Nomor Telepon',
            'Paket Menu Katering',
            'Jumlah (Box)',
            'Total Tagihan (Rp)',
            'Status Pembayaran',
            'Status Operasional'
        ];
    }

    /**
     * Pasangkan data field tabel ke kolom Excel
     */
    public function map($order): array
    {
        $paymentStatus = match($order->payment_status) {
            'fully_paid' => 'Lunas Total',
            'dp_paid'    => 'DP Lunas',
            default      => 'Belum Bayar',
        };

        return [
            '#' . $order->order_number,
            $order->event_date ? \Carbon\Carbon::parse($order->event_date)->format('d-m-Y') : '-',
            $order->contact_name ?? ($order->user->name ?? '-'),
            $order->contact_phone ?? '-',
            $order->package->name ?? 'Paket Kustom',
            $order->quantity,
            $order->total_amount,
            $paymentStatus,
            strtoupper($order->status)
        ];
    }

    /**
     * Format kolom agar angka tampil rapi di Excel
     */
    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    /**
     * Bikin baris pertama (Header) jadi tebal/bold
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}