<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Revenue, COGS, Expenditure, Loan, Investor, Product};
use App\Exports\BalanceSheetExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BalanceSheetController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->start_date ?? now()->startOfYear();
        $end = $request->end_date ?? now()->endOfYear();

        // 🏦 Revenue & Cash
        $totalRevenue = Revenue::whereBetween('date', [$start, $end])->sum('amount');
        $bankCash = Revenue::where('payment_method', 'bank')->sum('amount');
        $pettyCash = Revenue::where('payment_method', 'petty_cash')->sum('amount');
        $cashAndEquivalents = $bankCash + $pettyCash;

        // 📦 Inventory (assumes 1 unit per product row)
        $inventory = Product::sum('unit_cost');

        // 💸 Liabilities
        $loanBalance = Loan::sum('amount');
        $operatingExpenses = Expenditure::whereBetween('date', [$start, $end])->sum('amount');

        // 🧾 COGS
        $totalCOGS = COGS::sum(DB::raw('unit_cost * quantity'));

        // 👥 Equity
        $investorShares = Investor::sum('contribution');
        
        // 📈 Retained Earnings = Revenue - (COGS + OPEX + Loans)
        $retainedEarnings = $totalRevenue - ($totalCOGS + $operatingExpenses + $loanBalance);

        // Calculate additional balance sheet items
        $stocks = $inventory; // Alias for clarity
        $taxes = $operatingExpenses * 0.1; // Estimated 10% tax rate
        $payables = $operatingExpenses * 0.2; // Estimated 20% payables

        return view('finance.reports.balance_sheet', compact(
            'start', 'end',
            'totalRevenue', 'cashAndEquivalents', 'inventory',
            'loanBalance', 'operatingExpenses', 'stocks', 'taxes', 'payables',
            'investorShares', 'retainedEarnings', 'bankCash'
        ));
    }

    public function export(Request $request)
    {
        $format = $request->format ?? 'xlsx';
        $start = $request->start_date ?? now()->startOfYear();
        $end = $request->end_date ?? now()->endOfYear();

        $totalRevenue = Revenue::whereBetween('date', [$start, $end])->sum('amount');
        $bankCash = Revenue::where('payment_method', 'bank')->sum('amount');
        $pettyCash = Revenue::where('payment_method', 'petty_cash')->sum('amount');
        $cashAndEquivalents = $bankCash + $pettyCash;

        $inventory = Product::sum('unit_cost');
        $stocks = $inventory; // Alias for clarity

        $loanBalance = Loan::sum('amount');
        $operatingExpenses = Expenditure::whereBetween('date', [$start, $end])->sum('amount');
        $totalCOGS = COGS::sum(DB::raw('unit_cost * quantity'));
        $investorShares = Investor::sum('contribution');
        $retainedEarnings = $totalRevenue - ($totalCOGS + $operatingExpenses + $loanBalance);

        // Calculate additional items
        $taxes = $operatingExpenses * 0.1; // Estimated 10% tax rate
        $payables = $operatingExpenses * 0.2; // Estimated 20% payables

        $data = [
            'bankCash' => $bankCash,
            'cashAndEquivalents' => $cashAndEquivalents,
            'inventory' => $inventory,
            'stocks' => $stocks,
            'loanBalance' => $loanBalance,
            'operatingExpenses' => $operatingExpenses,
            'taxes' => $taxes,
            'payables' => $payables,
            'investorShares' => $investorShares,
            'retainedEarnings' => $retainedEarnings,
            'totalRevenue' => $totalRevenue,
            'totalCOGS' => $totalCOGS,
            'startDate' => $start,
            'endDate' => $end,
        ];

        $filename = 'Balance_Sheet_' . now()->format('Y_m_d') . '.' . $format;

        if ($format === 'pdf') {
            return $this->exportPDF($data, $filename);
        }

        return Excel::download(new BalanceSheetExport($data), $filename . '.xlsx');
    }

    private function exportPDF($data, $filename)
    {
        // For PDF export, you can use DomPDF or similar
        // This is a basic implementation
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('finance.reports.balance_sheet_pdf', compact('data'));
        
        return $pdf->download($filename);
    }
}