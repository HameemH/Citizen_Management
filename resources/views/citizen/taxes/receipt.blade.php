<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - {{ $payment->reference ?? $payment->id }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f3f4f6; margin: 0; padding: 20px; }
        .receipt { max-width: 720px; margin: 0 auto; background: #fff; border-radius: 12px; padding: 32px; box-shadow: 0 10px 25px rgba(15,23,42,0.08); }
        .receipt h1 { margin: 0; font-size: 24px; color: #111827; }
        .meta { margin-top: 8px; font-size: 14px; color: #6b7280; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-top: 24px; }
        .card { border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; background: #f9fafb; }
        .card p { margin: 0; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: #9ca3af; }
        .card h2 { margin: 6px 0 0; font-size: 18px; color: #111827; }
        table { width: 100%; border-collapse: collapse; margin-top: 24px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
        th { text-transform: uppercase; font-size: 12px; letter-spacing: 0.05em; color: #6b7280; }
        .actions { margin-top: 24px; display: flex; justify-content: flex-end; gap: 12px; }
        button, a.button { border: none; background: #059669; color: #fff; padding: 10px 20px; border-radius: 999px; cursor: pointer; font-weight: 600; text-decoration: none; }
        .note { margin-top: 16px; font-size: 12px; color: #6b7280; text-align: center; }
    </style>
</head>
<body>
    <div class="receipt">
        <h1>Payment Receipt</h1>
        <p class="meta">Receipt #: {{ $payment->reference ?? ('PMT-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT)) }} · Generated {{ now()->format('M d, Y H:i') }}</p>

        <div class="grid">
            <div class="card">
                <p>Amount Paid</p>
                <h2>BDT {{ number_format($payment->amount, 2) }}</h2>
            </div>
            <div class="card">
                <p>Paid On</p>
                <h2>{{ optional($payment->paid_at)->format('M d, Y H:i') ?? '—' }}</h2>
            </div>
            <div class="card">
                <p>Method</p>
                <h2>{{ strtoupper($payment->method ?? 'manual') }}</h2>
            </div>
            <div class="card">
                <p>Assessment</p>
                <h2>{{ $payment->assessment?->fiscal_year ?? 'N/A' }}</h2>
            </div>
        </div>

        <table>
            <tr>
                <th>Property</th>
                <td>{{ $payment->assessment?->property?->title }} ({{ $payment->assessment?->property?->city }})</td>
            </tr>
            <tr>
                <th>Payer</th>
                <td>{{ auth()->user()->display_name }}</td>
            </tr>
            <tr>
                <th>Reference</th>
                <td>{{ $payment->reference ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Tax Amount</th>
                <td>BDT {{ number_format($payment->assessment?->tax_amount ?? 0, 2) }}</td>
            </tr>
            <tr>
                <th>Notes</th>
                <td>{{ $payment->notes ?? '—' }}</td>
            </tr>
        </table>

        <div class="actions">
            <button onclick="window.print()">Print Receipt</button>
            <a href="{{ route('citizen.taxes.index') }}" class="button" style="background:#1d4ed8">Back to Taxes</a>
        </div>

        <p class="note">This receipt confirms that Dhaka City Corporation has received your property tax payment. Keep it for your records.</p>
    </div>
</body>
</html>
