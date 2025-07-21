<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Balance Sheet - {{ now()->format('Y-m-d') }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .title { text-align: center; font-weight: bold; font-size: 16px; margin-bottom: 20px; }
        .section { margin-top: 20px; }
        .section h4 { border-bottom: 1px solid #333; font-size: 14px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="title">REDVERS BATTERY SYSTEM <br> Balance Sheet</div>
    <div><strong>Period:</strong> {{ $data['startDate'] ?? 'N/A' }} to {{ $data['endDate'] ?? 'N/A' }}</div>
    <div><strong>Generated:</strong> {{ now()->format('Y-m-d H:i:s') }}</div>

    <div class="section">
        <h4>Assets</h4>
        <div class="row"><span>Cash & Bank</span><span>UGX {{ number_format($data['bankCash'] ?? 0) }}</span></div>
        <div class="row"><span>Inventory / Stock</span><span>UGX {{ number_format($data['stocks'] ?? 0) }}</span></div>
        <div class="row bold"><span>Total Assets</span><span>UGX {{ number_format(($data['bankCash'] ?? 0) + ($data['stocks'] ?? 0)) }}</span></div>
    </div>

    <div class="section">
        <h4>Liabilities</h4>
        <div class="row"><span>Loans</span><span>UGX {{ number_format($data['loanBalance'] ?? 0) }}</span></div>
        <div class="row"><span>Taxes (est.)</span><span>UGX {{ number_format($data['taxes'] ?? 0) }}</span></div>
        <div class="row"><span>Payables</span><span>UGX {{ number_format($data['payables'] ?? 0) }}</span></div>
        <div class="row bold"><span>Total Liabilities</span><span>UGX {{ number_format(($data['loanBalance'] ?? 0) + ($data['taxes'] ?? 0) + ($data['payables'] ?? 0)) }}</span></div>
    </div>

    <div class="section">
        <h4>Equity</h4>
        <div class="row"><span>Investor Contributions</span><span>UGX {{ number_format($data['investorShares'] ?? 0) }}</span></div>
        <div class="row"><span>Retained Earnings</span><span>UGX {{ number_format($data['retainedEarnings'] ?? 0) }}</span></div>
        <div class="row bold"><span>Total Equity</span><span>UGX {{ number_format(($data['investorShares'] ?? 0) + ($data['retainedEarnings'] ?? 0)) }}</span></div>
    </div>

    <div class="section bold">
        <div class="row"><span>Total Liabilities + Equity</span><span>
            UGX {{ number_format(($data['loanBalance'] ?? 0) + ($data['taxes'] ?? 0) + ($data['payables'] ?? 0) + ($data['investorShares'] ?? 0) + ($data['retainedEarnings'] ?? 0)) }}
        </span></div>
    </div>

</body>
</html>
