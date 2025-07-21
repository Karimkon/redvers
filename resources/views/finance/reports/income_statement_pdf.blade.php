<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Income Statement PDF</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .section { margin-top: 20px; }
        .title { text-align: center; font-size: 18px; font-weight: bold; }
        .row { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="title">Redvers Income Statement</div>
    <p><strong>Period:</strong> {{ $data['startDate'] ?? now()->startOfYear() }} - {{ $data['endDate'] ?? now()->endOfYear() }}</p>

    <div class="section">
        <h4>Revenue</h4>
        <div class="row"><span>Product Revenue</span><span>UGX {{ number_format($data['product_revenue'] ?? 0) }}</span></div>
        <div class="row"><span>Charging Revenue</span><span>UGX {{ number_format($data['charging_revenue'] ?? 0) }}</span></div>
        <div class="row"><span>Grants</span><span>UGX {{ number_format($data['grant_revenue'] ?? 0) }}</span></div>
        <div class="row"><span>Shareholder Contribution</span><span>UGX {{ number_format($data['shareholder_contribution'] ?? 0) }}</span></div>
        <div class="row bold"><span>Total Revenue</span><span>UGX {{ number_format($data['totalRevenue'] ?? 0) }}</span></div>
    </div>

    <div class="section">
        <h4>Cost of Goods Sold</h4>
        @foreach($data['cogs_items'] ?? [] as $item)
            <div class="row"><span>{{ $item->name }}</span><span>UGX {{ number_format($item->amount ?? 0) }}</span></div>
        @endforeach
        <div class="row bold"><span>Total COGS</span><span>UGX {{ number_format($data['totalCOGS'] ?? 0) }}</span></div>
    </div>

    <div class="section">
        <h4>Operating Expenses</h4>
        @foreach($data['expenses'] ?? [] as $exp)
            <div class="row"><span>{{ $exp->category }}</span><span>UGX {{ number_format($exp->amount ?? 0) }}</span></div>
        @endforeach
        <div class="row bold"><span>Total Expenses</span><span>UGX {{ number_format($data['totalExpenses'] ?? 0) }}</span></div>
    </div>

    <div class="section">
        <h4>Summary</h4>
        <div class="row"><span>EBITDA</span><span>UGX {{ number_format($data['ebitda'] ?? 0) }}</span></div>
        <div class="row"><span>Depreciation</span><span>UGX {{ number_format($data['depreciation'] ?? 0) }}</span></div>
        <div class="row"><span>Loan Interest</span><span>UGX {{ number_format($data['loan_interest'] ?? 0) }}</span></div>
        <div class="row"><span>EBT</span><span>UGX {{ number_format($data['ebt'] ?? 0) }}</span></div>
        <div class="row"><span>Tax</span><span>UGX {{ number_format($data['tax'] ?? 0) }}</span></div>
        <div class="row bold"><span>Net Income</span><span>UGX {{ number_format($data['net_income'] ?? 0) }}</span></div>
    </div>
</body>
</html>
