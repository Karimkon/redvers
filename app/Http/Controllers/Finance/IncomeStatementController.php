<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Revenue;
use App\Models\COGS;
use App\Models\Expenditure;
use App\Models\Depreciation;
use App\Models\Loan;
use App\Models\Investor;
use Illuminate\Support\Facades\DB;
use App\Exports\IncomeStatementExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class IncomeStatementController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->start_date ?? now()->startOfYear();
        $end = $request->end_date ?? now()->endOfYear();

        $start = \Carbon\Carbon::parse($start)->format('Y-m-d');
        $end = \Carbon\Carbon::parse($end)->format('Y-m-d');


        // 📈 Revenue
        $totalRevenue = Revenue::whereBetween('date', [$start, $end])->sum('amount');

        // 📉 COGS
        $totalCOGS = COGS::whereBetween('date', [$start, $end])->sum(\DB::raw('unit_cost * quantity'));

        // 🧾 Operating Expenses
        $operatingExpenses = Expenditure::whereBetween('date', [$start, $end])->sum('amount');

        // 🏚 Depreciation
        $depreciation = Depreciation::whereBetween('start_date', [$start, $end])->sum('initial_value') * 0.10; // Adjust rate if needed

        // 💳 Loan Interest
        $loanInterest = Loan::whereBetween('issued_date', [$start, $end])->sum('interest_paid');


        // Calculations
        $grossProfit = $totalRevenue - $totalCOGS;
        $shareholderContribution = Investor::sum('contribution');
        $ebitda = $grossProfit - $operatingExpenses;
        $preTaxIncome = $ebitda - ($depreciation + $loanInterest);
        $tax = $preTaxIncome * 0.30;
        $netIncome = $preTaxIncome - $tax;

        return view('finance.reports.pdf.income_statement', compact(
            'totalRevenue', 'totalCOGS', 'operatingExpenses',
            'grossProfit', 'ebitda', 'preTaxIncome',
            'depreciation', 'loanInterest', 'tax', 'netIncome',
            'start', 'end'
        ));
    }

public function export(Request $request, $format)
{
    $start = $request->start_date ?? now()->startOfYear();
    $end = $request->end_date ?? now()->endOfYear();

    // 💰 Revenue by source
    $revenues = Revenue::whereBetween('date', [$start, $end])
        ->selectRaw('source, SUM(amount) as amount')
        ->groupBy('source')
        ->pluck('amount', 'source');

    $productRevenue = $revenues['Product Revenue'] ?? 0;
    $grants = $revenues['Grants'] ?? 0;
    $loanInterest = Loan::whereBetween('issued_date', [$start, $end])->sum('interest_paid'); // ✅ Expense
    $shareholderContribution = Investor::whereBetween('date', [$start, $end])->sum('contribution');
    $chargingRevenue = \App\Models\Payment::where('status', 'completed')
    ->whereHas('swap') // only payments linked to swaps
    ->sum('amount');



    $totalRevenue = $revenues->sum() + $chargingRevenue + $shareholderContribution;
    $totalLoanAmount = \App\Models\Loan::where('status', 'active')->sum('amount'); // All active/total loans


    // 🧾 COGS items grouped by product
    $cogsItems = DB::table('c_o_g_s')
        ->join('products', 'c_o_g_s.product_id', '=', 'products.id')
        ->whereBetween('c_o_g_s.date', [$start, $end])
        ->select('products.name as name', DB::raw('SUM(c_o_g_s.unit_cost * c_o_g_s.quantity) as amount'))
        ->groupBy('products.name')
        ->get();

    $totalCOGS = $cogsItems->sum('amount');
    $grantRevenue = \App\Models\Revenue::sum('amount');


    // 💸 Expenses
    $expenses = Expenditure::whereBetween('date', [$start, $end])
        ->selectRaw('category, SUM(amount) as amount')
        ->groupBy('category')
        ->get();
    $totalExpenses = $expenses->sum('amount');

    // 📉 Depreciation
    $depreciation = Depreciation::whereBetween('start_date', [$start, $end])->sum('initial_value') * 0.10;

    // 💳 Loan Interest
    $loanInterest = Loan::whereBetween('issued_date', [$start, $end])->sum('interest_paid');

    // 📊 Calculations
    $grossProfit = $totalRevenue - $totalCOGS;
    $ebitda = $grossProfit - $totalExpenses;
    $ebit = $ebitda - $depreciation;
    $loanPrincipal = Loan::whereBetween('issued_date', [$start, $end])->sum('amount');
$loanInterest = Loan::whereBetween('issued_date', [$start, $end])->sum('interest_paid');
$ebt = $ebit - ($loanPrincipal + $loanInterest);

    $tax = 0; // Show 0% tax

    $netIncome = $ebt - $tax;


    $data = [
        'product_revenue' => $productRevenue,
        'charging_revenue' => $chargingRevenue,
        'shareholder_contribution' => $shareholderContribution,
        'totalRevenue' => $totalRevenue,
        'loan_amount' => $totalLoanAmount,
        'loan_principal' => $loanPrincipal,
        'loan_interest' => $loanInterest,
        'ebt' => $ebt,

        'cogs_items' => $cogsItems,
        'totalCOGS' => $totalCOGS,

        'expenses' => $expenses,
        'totalExpenses' => $totalExpenses,
        'ebitda' => $ebitda,
        'ebt' => $ebt,
        'grant_revenue' => $grantRevenue,
        'depreciation' => $depreciation,
        'tax' => $tax,
        'net_income' => $netIncome,
    ];

    if ($format === 'pdf') {
        return $this->exportPDF($data);
    }

    return Excel::download(new IncomeStatementExport($data), 'Income_Statement_' . now()->format('Y_m_d') . '.xlsx');
}

private function exportPDF($data)
{
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('finance.reports.income_statement_pdf', compact('data'));
    return $pdf->download('Income_Statement_' . now()->format('Y_m_d') . '.pdf');
}




}
