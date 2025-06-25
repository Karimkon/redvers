<!-- resources/views/admin/spares/exports/pdf.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Spare Parts Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        h2 {
            color: #0d9488;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        th, td {
            border: 1px solid #999;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
        }
    </style>
</head>
<body>
    <h2>Spare Parts Report</h2>
    <p><strong>Period:</strong> {{ $from }} to {{ $to }}</p>

    <h4>Stock Received</h4>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Part</th>
                <th>Shop</th>
                <th>Quantity</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stock as $entry)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $entry->part->name }}</td>
                    <td>{{ $entry->shop->name }}</td>
                    <td>{{ $entry->quantity }}</td>
                    <td>{{ \Carbon\Carbon::parse($entry->received_at)->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No stock received during this period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h4>Items Sold</h4>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Part</th>
                <th>Shop</th>
                <th>Quantity</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $sale->part->name }}</td>
                    <td>{{ $sale->shop->name }}</td>
                    <td>{{ $sale->quantity }}</td>
                    <td>{{ \Carbon\Carbon::parse($sale->sold_at)->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No sales recorded during this period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
