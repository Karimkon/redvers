<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\StockEntry;
use Illuminate\Http\Request;
use PDF;
use Excel;

class SpareExportController extends Controller
{
    public function exportPdf(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to = $request->to ?? now()->endOfMonth()->toDateString();

        $sales = Sale::whereBetween('sold_at', [$from, $to])->with('part', 'shop')->get();
        $stock = StockEntry::whereBetween('received_at', [$from, $to])->with('part', 'shop')->get();

        $pdf = PDF::loadView('admin.spares.exports.pdf', compact('sales', 'stock', 'from', 'to'));
        return $pdf->download('spare-report-' . now()->format('Ymd_His') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to = $request->to ?? now()->endOfMonth()->toDateString();

        $filename = 'spare-report-' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new \App\Exports\SpareReportExport($from, $to), $filename);
    }
}
