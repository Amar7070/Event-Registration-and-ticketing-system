<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 30px; }
        .details { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .totals { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Invoice</h1>
        <p>SmartEvent Ticketing Platform</p>
    </div>

    <div class="details">
        <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
        <p><strong>Date:</strong> {{ $invoice->created_at->format('M d, Y') }}</p>
        <p><strong>Billed To:</strong> {{ $invoice->user->name }} ({{ $invoice->user->email }})</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->line_items as $item)
            <tr>
                <td>{{ $item['description'] }}</td>
                <td>{{ number_format($item['amount'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p><strong>Subtotal:</strong> {{ number_format($invoice->subtotal, 2) }}</p>
        <p><strong>Tax:</strong> {{ number_format($invoice->tax, 2) }}</p>
        <p><strong>Total:</strong> {{ number_format($invoice->total, 2) }} {{ $payment->currency }}</p>
    </div>
</body>
</html>
