@extends('admin.layouts.app')

@section('title', 'View Swap')

@section('content')
<h2>Swap Details</h2>

<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <td>{{ $swap->id }}</td>
    </tr>
    <tr>
        <th>Rider</th>
        <td>{{ $swap->rider->name ?? 'N/A' }} ({{ $swap->rider->phone ?? 'N/A' }})</td>
    </tr>

    <tr>
    <th>Battery Issued: (Swap Specific)</th>
              <td>{{ $swap->batteryIssued->serial_number ?? 'N/A' }}</td>

    </tr>

    <tr>
    <th>Battery Returned: (Swap Specific)</th>
        <td>{{ $swap->returnedBattery->serial_number ?? 'None' }}</td>
    </tr>


    <tr>
        <th>Station</th>
        <td>{{ $swap->station->name ?? 'N/A' }}</td>
    </tr>
    <tr>
        <th>Agent</th>
        <td>{{ $swap->agent->name ?? 'N/A' }}</td>
    </tr>   
    <tr>
        <th>Battery Percentage</th>
        <td>{{ $swap->percentage_difference }}%</td>
    </tr>
    <tr>
        <th>Payable Amount</th>
        <td>{{ number_format($swap->payable_amount, 2) }} UGX</td>
    </tr>
    <tr>
        <th>Payment Method</th>
        <td>{{ ucfirst($swap->payment_method) ?? 'None' }}</td>
    </tr>
    <tr>
        <th>Swapped At</th>
        <td>{{ $swap->swapped_at }}</td>
    </tr>
</table>

<a href="{{ route('admin.swaps.index') }}" class="btn btn-secondary">Back to List</a>
@endsection
