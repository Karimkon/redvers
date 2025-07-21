<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BalanceSheetExport implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $totalAssets = ($this->data['bankCash'] ?? 0) + ($this->data['stocks'] ?? 0);
        $totalLiabilities = ($this->data['loanBalance'] ?? 0) + ($this->data['taxes'] ?? 0) + ($this->data['payables'] ?? 0);
        $totalEquity = ($this->data['investorShares'] ?? 0) + ($this->data['retainedEarnings'] ?? 0);
        $totalLiabilitiesAndEquity = $totalLiabilities + $totalEquity;

        return [
            ['REDVERS BALANCE SHEET'],
            ['Period: ' . ($this->data['startDate'] ?? 'N/A') . ' to ' . ($this->data['endDate'] ?? 'N/A')],
            ['Generated on: ' . now()->format('Y-m-d H:i:s')],
            [''],
            
            // ASSETS SECTION
            ['ASSETS', '', '', ''],
            ['Current Assets:', '', '', ''],
            ['Cash & Bank', '', '', 'UGX ' . number_format($this->data['bankCash'] ?? 0)],
            ['Inventory / Stock', '', '', 'UGX ' . number_format($this->data['stocks'] ?? 0)],
            ['Total Assets', '', '', 'UGX ' . number_format($totalAssets)],
            [''],
            
            // LIABILITIES SECTION
            ['LIABILITIES', '', '', ''],
            ['Current Liabilities:', '', '', ''],
            ['Loans Payable', '', '', 'UGX ' . number_format($this->data['loanBalance'] ?? 0)],
            ['Taxes Payable (Est.)', '', '', 'UGX ' . number_format($this->data['taxes'] ?? 0)],
            ['Accounts Payable', '', '', 'UGX ' . number_format($this->data['payables'] ?? 0)],
            ['Total Liabilities', '', '', 'UGX ' . number_format($totalLiabilities)],
            [''],
            
            // EQUITY SECTION
            ['EQUITY', '', '', ''],
            ['Investor Contributions', '', '', 'UGX ' . number_format($this->data['investorShares'] ?? 0)],
            ['Retained Earnings', '', '', 'UGX ' . number_format($this->data['retainedEarnings'] ?? 0)],
            ['Total Equity', '', '', 'UGX ' . number_format($totalEquity)],
            [''],
            
            // TOTAL
            ['TOTAL LIABILITIES & EQUITY', '', '', 'UGX ' . number_format($totalLiabilitiesAndEquity)],
            [''],
            
            // BALANCE CHECK
            ['BALANCE CHECK:', '', '', ''],
            ['Total Assets', '', '', 'UGX ' . number_format($totalAssets)],
            ['Total Liab. & Equity', '', '', 'UGX ' . number_format($totalLiabilitiesAndEquity)],
            ['Difference', '', '', 'UGX ' . number_format($totalAssets - $totalLiabilitiesAndEquity)],
            [''],
            
            // NOTES
            ['NOTES:', '', '', ''],
            ['- Revenue Total: UGX ' . number_format($this->data['totalRevenue'] ?? 0), '', '', ''],
            ['- COGS Total: UGX ' . number_format($this->data['totalCOGS'] ?? 0), '', '', ''],
            ['- Operating Expenses: UGX ' . number_format($this->data['operatingExpenses'] ?? 0), '', '', ''],
            ['- Taxes estimated at 10% of OPEX', '', '', ''],
            ['- Payables estimated at 20% of OPEX', '', '', ''],
            ['- Retained Earnings = Revenue - COGS - OPEX - Loans', '', '', ''],
        ];
    }

    public function title(): string
    {
        return 'Balance Sheet';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 35,
            'B' => 10,
            'C' => 10,
            'D' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Main title
            1 => ['font' => ['bold' => true, 'size' => 16]],
            2 => ['font' => ['bold' => true, 'size' => 12]],
            3 => ['font' => ['italic' => true]],
            
            // Section headers
            5 => ['font' => ['bold' => true, 'size' => 14]], // ASSETS
            11 => ['font' => ['bold' => true, 'size' => 14]], // LIABILITIES
            18 => ['font' => ['bold' => true, 'size' => 14]], // EQUITY
            
            // Subsection headers
            6 => ['font' => ['bold' => true]], // Current Assets
            12 => ['font' => ['bold' => true]], // Current Liabilities
            
            // Totals
            9 => ['font' => ['bold' => true]], // Total Assets
            16 => ['font' => ['bold' => true]], // Total Liabilities
            21 => ['font' => ['bold' => true]], // Total Equity
            24 => ['font' => ['bold' => true, 'size' => 12]], // TOTAL LIAB & EQUITY
            
            // Balance check
            26 => ['font' => ['bold' => true]], // BALANCE CHECK
            
            // Notes
            31 => ['font' => ['bold' => true]], // NOTES
        ];
    }
}