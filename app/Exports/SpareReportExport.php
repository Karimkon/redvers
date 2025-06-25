<?php

namespace App\Exports;

use App\Models\Sale;
use App\Models\StockEntry;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SpareReportExport implements FromView
{
    protected $from;
    protected $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function view(): View
    {
        $sales = Sale::with('part', 'shop')
            ->whereBetween('sold_at', [$this->from, $this->to])
            ->get();

        $stock = StockEntry::with('part', 'shop')
            ->whereBetween('received_at', [$this->from, $this->to])
            ->get();

        return view('admin.spares.exports.excel', [
            'sales' => $sales,
            'stock' => $stock,
            'from' => $this->from,
            'to' => $this->to,
        ]);
    }
}
