<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Wallet;
use App\Models\User;
use App\Models\WalletTransaction;

class AdminWalletController extends Controller
{
    /**
     * Display paginated list of all wallets with user info.
     */
    public function index()
    {
        $wallets = Wallet::with('user')->paginate(20);
        $totalBalance = Wallet::sum('balance');

        return view('admin.wallets.index', compact('wallets', 'totalBalance'));
    }

    /**
     * Display paginated list of wallets for top-up selection.
     * (Could be merged with index() if views are similar)
     */
    public function topUpIndex()
    {
        $wallets = Wallet::with('user')->paginate(20);

        return view('admin.wallets.topup-index', compact('wallets'));
    }

    /**
     * Show top-up form for a specific user.
     */
    public function topUpForm(User $user)
    {
        return view('admin.wallets.topup', compact('user'));
    }

    /**
     * Process the top-up request and update user's wallet balance.
     */
    public function topUpStore(Request $request, User $user)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'reason' => ['nullable', 'string', 'max:255'],
        ], [
            'amount.required' => 'Please enter a top-up amount.',
            'amount.numeric' => 'Amount must be a valid number.',
            'amount.min' => 'Amount must be at least 1 UGX.',
        ]);

        DB::transaction(function () use ($user, $validated) {
            // Create wallet if missing
            $wallet = $user->wallet()->firstOrCreate(['user_id' => $user->id]);

            // Increment wallet balance
            $wallet->increment('balance', $validated['amount']);

            // Log transaction
            WalletTransaction::create([
                'user_id'    => $user->id,
                'amount'     => $validated['amount'],
                'reason'     => $validated['reason'] ?? 'Admin top-up',
                'reference'  => 'ADMIN_TOPUP_' . time(),
                'description'=> 'Wallet topped up by admin: ' . auth()->user()->name,
            ]);
        });

        return redirect()
            ->route('admin.wallets.show', $user)
            ->with('success', 'Wallet credited successfully! Added UGX ' . number_format($validated['amount'], 0));
    }

    /**
     * Show wallet ledger with paginated transaction logs for a user.
     */
    public function show(User $user)
    {
        $wallet = $user->wallet;

        if (!$wallet) {
            return redirect()
                ->route('admin.wallets.index')
                ->with('warning', 'This user does not have a wallet yet.');
        }

        // Paginate transaction logs, newest first
        $logs = WalletTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.wallets.show', compact('wallet', 'user', 'logs'));
    }
}
