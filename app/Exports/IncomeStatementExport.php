<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IncomeStatementExport implements FromArray, WithTitle, WithStyles
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $grossProfit = ($this->data['totalRevenue'] ?? 0) - ($this->data['totalCOGS'] ?? 0);

        return [
            ['REDVERS INCOME STATEMENT'],
            [''],
            ['Revenue'],
            ['Product Revenue (E-Bikes)', '', '', 'UGX' . number_format($this->data['product_revenue'] ?? 0)],
            ['Charging Station Revenue', '', '', 'UGX' . number_format($this->data['charging_revenue'] ?? 0)],
            ['Grants & Contributions', '', '', 'UGX' . number_format($this->data['grant_revenue'] ?? 0)],
            ['Loans', '', '', 'Not Applicable (Liability)'],
            ['Shareholder contribution', '', '', 'UGX' . number_format($this->data['shareholder_contribution'] ?? 0)],
            ['Total Revenue', '', '', 'UGX' . number_format($this->data['totalRevenue'] ?? 0)],

            [''],
            ['Cost of Goods Sold (COGS)'],
            ...collect($this->data['cogs_items'] ?? [])->map(fn($item) => [$item->name ?? 'N/A', '', '', 'UGX' . number_format($item->amount ?? 0)])->toArray(),
            ['Total COGS', '', '', 'UGX' . number_format($this->data['totalCOGS'] ?? 0)],

            [''],
            ['Gross Profit', '', '', 'UGX' . number_format($grossProfit)],

            [''],
            ['Operating Expenses (OPEX)'],
            ...collect($this->data['expenses'] ?? [])->map(fn($item) => [$item->category ?? 'N/A', '', '', 'UGX' . number_format($item->amount ?? 0)])->toArray(),
            ['Total OPEX', '', '', 'UGX' . number_format($this->data['totalExpenses'] ?? 0)],

            [''],
            ['EBITDA', '', '', 'UGX' . number_format($this->data['ebitda'] ?? 0)],
            ['Depreciation & Amortization', '', '', 'UGX' . number_format($this->data['depreciation'] ?? 0)],
            ['EBIT (Operating Income)', '', '', 'UGX' . number_format($this->data['ebt'] ?? 0)],
            ['Loan', '', '', 'UGX' . number_format($this->data['loan_principal'] ?? 0)],
            ['Interest Expense', '', '', 'UGX' . number_format($this->data['loan_interest'] ?? 0)],
            ['EBT (Earnings Before Taxes)', '', '', 'UGX' . number_format($this->data['ebt'] ?? 0)],
            ['Income Tax (' . (isset($this->data['tax_rate']) ? ($this->data['tax_rate'] * 100) . '%' : '0%') . ')', '', '', 'UGX' . number_format($this->data['tax'] ?? 0)],
            ['Net Income', '', '', 'UGX' . number_format($this->data['net_income'] ?? 0)],

            [''],
            [''],
            ['🧾 Additional Disclosures'],
            ['Outstanding Loan Obligations', '', '', 'UGX' . number_format($this->data['loan_amount'] ?? 0)],
        ];
    }

    public function title(): string
    {
        return 'Income Statement';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            3 => ['font' => ['bold' => true]],
            11 => ['font' => ['bold' => true]],
            13 => ['font' => ['bold' => true]],
            16 => ['font' => ['bold' => true]],
            18 => ['font' => ['bold' => true]],
            21 => ['font' => ['bold' => true]],
        ];
    }
}
