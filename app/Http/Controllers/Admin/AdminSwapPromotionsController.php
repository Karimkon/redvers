<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SwapPromotion;
use App\Models\User;
use App\Services\PesapalService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AdminSwapPromotionsController extends Controller
{
    public function index(Request $request)
    {
        $query = SwapPromotion::with(['rider', 'agent']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $promotions = $query
        ->orderByRaw("FIELD(status, 'active', 'pending', 'expired')")
        ->latest('starts_at')
        ->paginate(15);


        // ✅ Only auto-expire, not auto-activate
        $promotions->getCollection()->transform(function ($promo) {
            if ($promo->status === 'active' && now()->gt($promo->ends_at)) {
                $promo->status = 'expired';
                $promo->save(); // Persist the expiry
            }

            // ❌ DO NOT auto-activate fbased on time alone
            return $promo;
        });

        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        $riders = User::where('role', 'rider')->get();
        $agents = User::where('role', 'agent')->get();

        return view('admin.promotions.create', compact('riders', 'agents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rider_id' => 'required|exists:users,id',
            'agent_id' => 'required|exists:users,id',
        ]);

        $promo = SwapPromotion::create([
            'rider_id' => $request->rider_id,
            'agent_id' => $request->agent_id,
            'starts_at' => now(),
            'ends_at' => now()->addDay(),
            'amount' => 25000,
            'status' => 'pending',
        ]);

        session([
            'pending_promo_id' => $promo->id,
            'pending_promo_reference' => 'PROMO-' . uniqid(),
        ]);

        return redirect()->route('admin.promotions.pesapal');
    }

    public function redirectToPayment()
    {
        $promoId = session('pending_promo_id');
        $reference = session('pending_promo_reference');

        if (!$promoId) {
            return redirect()->back()->with('error', 'No promo session found.');
        }

        $promotion = SwapPromotion::with('rider')->findOrFail($promoId);
        $rider = $promotion->rider;
        $amount = $promotion->amount;

        return view('admin.promotions.pesapal', compact('promotion', 'rider', 'amount', 'reference'));
    }

    public function payViaPesapal(Request $request)
    {
        $promoId = session('pending_promo_id');
        $reference = session('pending_promo_reference');

        $promotion = SwapPromotion::with('rider')->findOrFail($promoId);
        $rider = $promotion->rider;

        try {
            $token = app(PesapalService::class)->getAccessToken();

            $response = Http::withToken($token)->post(config('pesapal.base_url') . '/api/Transactions/SubmitOrderRequest', [
                "id" => Str::uuid()->toString(),
                "currency" => "UGX",
                "amount" => $promotion->amount,
                "description" => "Unlimited Swap Promotion",
                "callback_url" => route('pesapal.promo.callback'),
                "notification_id" => "34f2ce63-9c4c-430d-adb8-dbba55243d85",
                "billing_address" => [
                    "email_address" => $rider->email,
                    "phone_number" => $rider->phone,
                    "first_name" => explode(' ', $rider->name)[0],
                    "last_name" => explode(' ', $rider->name)[1] ?? '',
                    "line_1" => "Redvers Promo",
                    "city" => "Kampala",
                    "state" => "Central",
                    "postal_code" => "256",
                    "zip_code" => "256",
                    "country_code" => "UG"
                ]
            ]);

            if ($response->successful()) {
                return redirect()->away($response['redirect_url']);
            }

            return back()->withErrors(['pesapal' => 'Pesapal initiation failed.']);
        } catch (\Exception $e) {
            \Log::error('Pesapal Promo Admin Error', ['error' => $e->getMessage()]);
            return back()->withErrors(['pesapal' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function show(SwapPromotion $promotion)
    {
        return view('admin.promotions.show', compact('promotion'));
    }

    public function edit(SwapPromotion $promotion)
    {
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, SwapPromotion $promotion)
    {
        $request->validate([
            'status' => 'required|in:pending,active,expired,cancelled',
        ]);

        $promotion->status = $request->status;
        $promotion->save();

        return redirect()->route('admin.promotions.index')->with('success', 'Promotion updated successfully.');
    }

    public function destroy(SwapPromotion $promotion)
    {
        $promotion->delete();
        return back()->with('success', 'Promotion deleted.');
    }
}
