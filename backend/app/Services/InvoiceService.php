<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public function generate(Payment $payment): Invoice
    {
        $invoice = Invoice::create([
            'payment_id' => $payment->id,
            'user_id' => $payment->user_id,
            'invoice_number' => 'INV-' . date('Y') . '-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT),
            'line_items' => $this->buildLineItems($payment),
            'subtotal' => $payment->amount - $payment->tax_amount,
            'tax' => $payment->tax_amount,
            'discount' => 0, // Default to 0, actual logic could be more complex
            'total' => $payment->amount,
        ]);
        
        // Generate PDF and store
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice', 'payment'));
        $path = "invoices/{$invoice->invoice_number}.pdf";
        Storage::disk('public')->put($path, $pdf->output());
        
        $invoice->update(['pdf_path' => $path]);
        
        return $invoice;
    }

    private function buildLineItems(Payment $payment): array
    {
        return [
            [
                'description' => "Ticket for Event ID {$payment->event_id}",
                'amount' => $payment->amount
            ]
        ];
    }
}
