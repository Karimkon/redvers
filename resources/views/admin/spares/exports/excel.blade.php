<!-- resources/views/admin/spares/exports/excel.blade.php -->
<table>
    <thead>
        <tr><th colspan="5">Stock Received ({{ $from }} to {{ $to }})</th></tr>
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
            <tr><td colspan="5">No stock entries.</td></tr>
        @endforelse
    </tbody>
</table>

<table>
    <thead>
        <tr><th colspan="5">Items Sold ({{ $from }} to {{ $to }})</th></tr>
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
            <tr><td colspan="5">No sales records.</td></tr>
        @endforelse
    </tbody>
</table>
