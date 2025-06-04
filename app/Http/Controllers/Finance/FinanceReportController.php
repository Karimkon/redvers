<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Swap;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FinanceReportController extends Controller
{
    public function index()
    {
        $totalPayments = Payment::where('status', 'completed')->sum('amount');
        $paymentCount = Payment::count();
        $swapCount = Swap::count();

        return view('finance.reports.index', compact(
            'totalPayments', 'paymentCount', 'swapCount'
        ));
    }

    public function download($type)
    {
        switch ($type) {
            case 'payments':
                $data = Payment::with('swap')->latest()->get();
                $pdf = Pdf::loadView('finance.reports.pdf.payments', ['payments' => $data]);
                return $pdf->download('payments_report.pdf');

            case 'swaps':
                $data = Swap::with(['riderUser', 'station'])->latest()->get();
                $pdf = Pdf::loadView('finance.reports.pdf.swaps', ['swaps' => $data]);
                return $pdf->download('swaps_report.pdf');

            default:
                return back()->with('error', 'Invalid report type.');
        }
    }
}
