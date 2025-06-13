<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BatteryDelivery;
use App\Models\Battery;
use App\Models\User;
use App\Models\Station;
use Illuminate\Http\Request;


class BatteryDeliveryController extends Controller
{
    // Show form to create delivery
    public function create()
    {
        $batteries = Battery::whereIn('status', ['charging', 'in_stock'])
            ->whereDoesntHave('latestDelivery', function ($query) {
                $query->where('returned_to_admin', false);
            })
            ->latest()
            ->get();


        $agents = User::where('role', 'agent')->with('station')->get();

        return view('admin.deliveries.create', compact('batteries', 'agents'));
    }

    // Store the delivery
    public function store(Request $request)
    {
        $request->validate([
            'battery_ids' => 'required|array',
            'battery_ids.*' => 'exists:batteries,id',
            'agent_id' => 'required|exists:users,id',
            'delivered_by' => 'nullable|string'
        ]);

        $agent = User::findOrFail($request->agent_id);
        $stationId = $agent->station_id;

        foreach ($request->battery_ids as $batteryId) {
            BatteryDelivery::create([
                'battery_id' => $batteryId,
                'delivered_to_agent_id' => $agent->id,
                'station_id' => $stationId,
                'delivery_code' => 'BATCH-' . strtoupper(uniqid()),
                'delivered_by' => $request->delivered_by,
            ]);
        }

        return redirect()->route('admin.deliveries.index')->with('success', 'Batteries dispatched successfully.');
    }

    // View all deliveries
    public function index()
    {
        $deliveries = BatteryDelivery::with(['battery', 'station', 'agent', 'returnedByAdmin'])
            ->whereNotNull('returned_at')
            ->latest('returned_at')
            ->get();

        return view('admin.deliveries.index', compact('deliveries'));
    }

    public function showReturns()
    {
        $deliveries = \App\Models\BatteryDelivery::with('battery', 'station', 'agent')
            ->where('received', true)
            ->where('returned_to_admin', false)
            ->latest()
            ->get();

        return view('admin.deliveries.returns', compact('deliveries'));
    }



    public function acceptReturns(Request $request)
    {
        $request->validate([
            'delivery_ids' => 'required|array',
            'delivery_ids.*' => 'exists:battery_deliveries,id',
        ]);

        $deliveries = \App\Models\BatteryDelivery::whereIn('id', $request->delivery_ids)->get();

        foreach ($deliveries as $delivery) {
            $delivery->update([
                'returned_to_admin' => true,
                'returned_at' => now(),
                'returned_by_admin_id' => auth()->id()
            ]);

            $delivery->battery->update([
                'current_station_id' => null,
                'status' => 'charging',
            ]);

        }

        return redirect()->route('admin.deliveries.returns')
            ->with('success', 'Selected batteries marked as returned.');
    }

    public function returnHistory()
    {
        $deliveries = \App\Models\BatteryDelivery::with('battery', 'station', 'agent', 'returnedByAdmin')
            ->where('returned_to_admin', true)
            ->latest('returned_at')
            ->get();

        return view('admin.deliveries.history', compact('deliveries'));
    }



}
