<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Summary - {{ $purchase->user->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #ccc; padding-bottom: 10px; }
        .logo { width: 100px; }
        .profile-pic { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid #ccc; }
        .info-section { margin-top: 20px; }
        .summary-box, .signature { margin-top: 30px; }
        .summary-box p, .info-section p { margin: 4px 0; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ccc; padding: 8px; }
        .table th { background: #f2f2f2; }
        .badge { padding: 3px 6px; border-radius: 4px; font-weight: bold; }
        .bg-primary { background-color: #007bff; color: white; }
        .bg-success { background-color: #28a745; color: white; }
        .bg-danger { background-color: #dc3545; color: white; }
        .footer { text-align: center; font-size: 10px; color: #777; margin-top: 40px; }
        .signature-line { border-top: 1px dashed #000; width: 200px; margin-top: 20px; }
    </style>
</head>
<body>

<table width="100%" style="margin-bottom: 20px;">
    <tr>
        {{-- Logo Left --}}
        <td align="left" style="width: 50%;">
            <img src="{{ public_path('images/redvers.jpeg') }}" style="height: 80px;">
        </td>

        {{-- Profile Photo Right --}}
        <td align="right" style="width: 50%;">
            @if($purchase->user->profile_photo)
                <img src="{{ public_path('storage/' . $purchase->user->profile_photo) }}"
                     style="height: 80px; width: 80px; border-radius: 50%; object-fit: cover; border: 2px solid #ccc;">
            @endif
        </td>
    </tr>
</table>



    {{-- Rider & Purchase Info --}}
    <div class="info-section">
        <h3>📄 Motorcycle Purchase Summary</h3>
        <p><strong>Rider:</strong> {{ $purchase->user->name }}</p>
        <p><strong>Phone:</strong> {{ $purchase->user->phone }}</p>
        <p><strong>Email:</strong> {{ $purchase->user->email }}</p>
        <p><strong>Motorcycle:</strong> {{ ucfirst($purchase->motorcycle->type) }}</p>
        <p><strong>Purchase Type:</strong> {{ ucfirst($purchase->purchase_type) }}</p>
        <p><strong>Total Price:</strong> UGX {{ number_format($purchase->total_price) }}</p>
        <p><strong>Initial Deposit:</strong> UGX {{ number_format($purchase->initial_deposit) }}</p>
        <p><strong>Amount Paid:</strong> UGX {{ number_format($purchase->amount_paid) }}</p>
        <p><strong>Remaining Balance:</strong> UGX {{ number_format($purchase->remaining_balance) }}</p>
        <p><strong>Status:</strong>
            <span class="badge bg-{{ $purchase->status === 'cleared' ? 'success' : ($purchase->status === 'defaulted' ? 'danger' : 'primary') }}">
                {{ ucfirst($purchase->status) }}
            </span>
        </p>
    </div>

    {{-- Payment History Table --}}
    @if($purchase->payments->count())
        <h4>💰 Payment History</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Amount (UGX)</th>
                    <th>Type</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase->payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_date }}</td>
                        <td>{{ number_format($payment->amount) }}</td>
                        <td>{{ ucfirst($payment->type) }}</td>
                        <td>{{ $payment->note ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Signature --}}
    <div class="signature">
        <p><strong>Authorized By:</strong></p>
        <div class="bold">Redvers Administrator - Fahad</div>
        <p>Date: {{ now()->format('d/m/Y') }}</p>
    </div>

    {{-- Footer --}}
    <div class="footer">
        Powered by Redvers Battery System. This document is system generated.
    </div>

</body>
</html>
