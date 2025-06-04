<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payments Report</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #444;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>
    <h2>Redvers Finance – Payments Report</h2>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Amount (UGX)</th>
                <th>Method</th>
                <th>Status</th>
                <th>Reference</th>
                <th>Initiated By</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $i => $payment)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ number_format($payment->amount) }}</td>
                    <td>{{ ucfirst($payment->method) }}</td>
                    <td>{{ ucfirst($payment->status) }}</td>
                    <td>{{ $payment->reference }}</td>
                    <td>{{ ucfirst($payment->initiated_by ?? 'admin') }}</td>
                    <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
