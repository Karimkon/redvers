<?php 
    
namespace App\Http\Controllers\Agent;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Swap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AgentSwapHistoryController extends Controller
{
    public function index(Request $request)
{
    $agentId = Auth::id();
    $query = Swap::with(['riderUser', 'station', 'motorcycleUnit']) // 👈 include relationship
                 ->where('agent_id', $agentId);

    if ($request->filled('search')) {
        $search = trim($request->search);

        $query->where(function ($q) use ($search) {
            $q->whereHas('riderUser', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            })
            ->orWhereHas('motorcycleUnit', function ($q) use ($search) {
                $q->where('number_plate', 'like', "%$search%");
                // optional: exact match on ID
                if (Str::startsWith($search, 'ID:')) {
                    $id = (int) trim(Str::after($search, 'ID:'));
                    $q->orWhere('id', $id);
                }
            })
            ->orWhere('motorcycle_unit_id', $search); // direct ID match fallback
        });
    }

    $swaps = $query->latest()->paginate(15);

    return view('agent.swap-history.index', compact('swaps'));
}


}
