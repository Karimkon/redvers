<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\BatteryDelivery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class AgentBatteryDeliveryController extends Controller
{
    public function index()
    {
        $agentId = Auth::id();
        $deliveries = BatteryDelivery::with('battery', 'station')
            ->where('delivered_to_agent_id', $agentId)
            ->latest()
            ->get();

        return view('agent.deliveries.index', compact('deliveries'));
    }

    public function receive(BatteryDelivery $delivery)
    {
        if ($delivery->received) {
            return back()->with('info', 'Battery already received.');
        }

        $delivery->update([
            'received' => true,
            'received_at' => now(),
        ]);

        $battery = $delivery->battery;
        $battery->update([
            'current_station_id' => $delivery->station_id,
            'status' => 'in_stock',
        ]);

        return back()->with('success', 'Battery received and added to your station stock.');
    }

    public function acceptMultiple(Request $request)
{
    $request->validate([
        'delivery_ids' => 'required|array',
        'delivery_ids.*' => 'exists:battery_deliveries,id'
    ]);

    foreach ($request->delivery_ids as $id) {
        $delivery = \App\Models\BatteryDelivery::find($id);

        if ($delivery && !$delivery->received) {
            $delivery->update([
                'received' => true,
                'received_at' => now(),
            ]);

            $battery = $delivery->battery;
            $battery->update([
                'current_station_id' => $delivery->station_id,
                'status' => 'in_stock',
            ]);
        }
    }

    return back()->with('success', 'Selected deliveries accepted.');
}

}
