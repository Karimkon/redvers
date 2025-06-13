<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Swap;
use App\Models\Battery;
use App\Models\Station;


use App\Http\Controllers\Agent\AgentSwapController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PesapalController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RiderController;
use App\Http\Controllers\Admin\SwapController;
use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\StationController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Agent\AgentDashboardController;
use App\Http\Controllers\Agent\AgentSwapHistoryController;
use App\Http\Controllers\Admin\BatteryController;
use App\Http\Controllers\Rider\RiderProfileController;
use App\Http\Controllers\Finance\FinanceDashboardController;
use App\Http\Controllers\Finance\FinanceReportController;
use App\Http\Controllers\Finance\FinancePaymentController;
use App\Http\Controllers\Finance\FinanceOverdueController;
use App\Http\Controllers\Finance\FollowUpController;
use App\Http\Controllers\Admin\MotorcyclePurchaseController;
use App\Http\Controllers\Admin\MotorcyclePaymentController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\MotorcycleUnitController;
use App\Http\Controllers\Finance\FinancePurchaseController;
use App\Services\PesapalService;
use App\Http\Controllers\PesapalTokenTestController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Admin\BatteryDeliveryController;
use App\Http\Controllers\Agent\AgentBatteryDeliveryController;










// Home
Route::get('/', fn () => view('welcome'));
Route::get('/pesapal/request-token', [PesapalTokenTestController::class, 'getToken']);

// Login views per role
Route::get('/admin/login', fn () => view('auth.admin-login'))->name('admin.login');
Route::get('/agent/login', fn () => view('auth.agent-login'))->name('agent.login');
Route::get('/rider/login', fn () => view('auth.rider-login'))->name('rider.login');
Route::get('/finance/login', fn () => view('auth.finance-login'))->name('finance.login');

Route::get('/pesapal/auth', [\App\Http\Controllers\PesapalController::class, 'authenticate'])->name('pesapal.auth');

Route::post('/pesapal/callback', [\App\Http\Controllers\PesapalController::class, 'handleCallback'])->name('pesapal.callback');

Route::get('/pesapal/test-submit', [PesapalController::class, 'testSubmitOrder'])->name('pesapal.test');

Route::post('/pesapal/ipn', [PesapalController::class, 'handleIPN'])->name('pesapal.ipn');


// Dedicated login submits
Route::post('/admin/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember')) && Auth::user()->role === 'admin') {
        $request->session()->regenerate();
        return redirect()->intended(route('admin.dashboard'));
    }

    Auth::logout();
    return redirect()->route('admin.login')->with('error', 'Only admins can login here.');
})->name('admin.login.submit');

Route::post('/agent/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember')) && Auth::user()->role === 'agent') {
        $request->session()->regenerate();
        return redirect()->intended(route('agent.dashboard'));
    }

    Auth::logout();
    return redirect()->route('agent.login')->with('error', 'Only agents can login here.');
})->name('agent.login.submit');

Route::post('/rider/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember')) && Auth::user()->role === 'rider') {
        $request->session()->regenerate();
        return redirect()->intended(route('rider.dashboard'));
    }

    Auth::logout();
    return redirect()->route('rider.login')->with('error', 'Only riders can login here.');
})->name('rider.login.submit');

Route::post('/finance/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember')) && Auth::user()->role === 'finance') {
        $request->session()->regenerate();
        return redirect()->intended(route('finance.dashboard'));
    }

    Auth::logout();
    return redirect()->route('finance.login')->with('error', 'Only finance staff can login here.');
})->name('finance.login.submit');


// Logout (shared)
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    /** -----------------------------------
     * 🔹 Admin Dashboard & Charts
     * ---------------------------------- */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/swaps/chart/data', [DashboardController::class, 'swapChartData'])->name('api.swaps.chart');

    /** -----------------------------------
     * 🔹 Core Resource Management
     * ---------------------------------- */
    Route::resource('riders', RiderController::class);
    Route::resource('agents', AgentController::class);
    Route::resource('stations', StationController::class);
    Route::resource('batteries', BatteryController::class);
    Route::resource('swaps', SwapController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('motorcycle-units', MotorcycleUnitController::class);

    /** -----------------------------------
     * 🔹 Motorcycle Purchase System
     * ---------------------------------- */
    Route::resource('purchases', MotorcyclePurchaseController::class);
    Route::post('/purchases/{purchase}/motorcycle-payments', [MotorcyclePaymentController::class, 'store'])
        ->name('motorcycle-payments.store');

    // Discounts
    Route::get('/purchases/{purchase}/discounts/create', [DiscountController::class, 'create'])->name('discounts.create');
    Route::post('/purchases/{purchase}/discounts', [DiscountController::class, 'store'])->name('discounts.store');

    /** -----------------------------------
     * 🔹 Battery History Tracking
     * ---------------------------------- */
    Route::get('/batteries/{battery}/history', [BatteryController::class, 'history'])->name('batteries.history');

    /** -----------------------------------
     * 🔹 Battery Delivery Routes
     * ---------------------------------- */
    Route::prefix('deliveries')->name('deliveries.')->group(function () {
        Route::get('/', [BatteryDeliveryController::class, 'index'])->name('index');
    Route::get('/create', [BatteryDeliveryController::class, 'create'])->name('create');
    Route::post('/', [BatteryDeliveryController::class, 'store'])->name('store');

    //Delivery Returns
    Route::get('/returns', [BatteryDeliveryController::class, 'showReturns'])->name('returns');
    Route::post('/accept-returns', [BatteryDeliveryController::class, 'acceptReturns'])->name('acceptReturns');
    Route::get('/return-history', [BatteryDeliveryController::class, 'returnHistory'])->name('history');


    });



    /** -----------------------------------
     * 🔹 Admin API Endpoints (AJAX support)
     * ---------------------------------- */

    // ✅ Get the rider’s last battery (used in swap form)
    Route::get('/api/rider-last-battery/{rider}', function ($riderId) {
        $lastSwap = \App\Models\Swap::where('rider_id', $riderId)->latest()->first();

        if (!$lastSwap) {
            return response()->json(null); // New rider (no previous swap)
        }

        $battery = \App\Models\Battery::find($lastSwap->battery_id);
        return response()->json($battery); // Will return battery object
    });

     // ✅ Admin API Route to get assigned motorcycle unit for a rider
Route::get('/admin/api/rider-motorcycle/{rider}', function ($riderId) {
    try {
        $purchase = \App\Models\Purchase::with('motorcycleUnit')
            ->where('user_id', $riderId)
            ->where('status', 'active')
            ->whereNotNull('motorcycle_unit_id')
            ->latest()
            ->first();

        \Log::info('🔍 Motorcycle Fetch Debug', [
            'rider_id' => $riderId,
            'purchase_found' => $purchase !== null,
            'motorcycle_unit_id' => optional($purchase)->motorcycle_unit_id,
            'unit_status' => optional($purchase->motorcycleUnit)->status ?? 'N/A',
            'number_plate' => optional($purchase->motorcycleUnit)->number_plate ?? 'N/A',
        ]);

        if (
            $purchase &&
            $purchase->motorcycleUnit &&
            $purchase->motorcycleUnit->status === 'assigned'
        ) {
            return response()->json([
                'id' => $purchase->motorcycleUnit->id,
                'number_plate' => $purchase->motorcycleUnit->number_plate,
            ]);
        }

        return response()->json(null);
    } catch (\Throwable $e) {
        \Log::error('❌ Motorcycle API Error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        return response()->json(['error' => 'Internal error'], 500);
    }
});






    // ✅ Get batteries available at a station (used when creating a swap)
    Route::get('/api/batteries-by-station/{station}', function ($stationId) {
        return \App\Models\Battery::where('current_station_id', $stationId)
            ->whereIn('status', ['in_stock', 'charging'])
            ->select('id', 'serial_number')
            ->get();
    })->name('api.batteries.by.station');

    Route::get('/chat', function () {
    $users = \App\Models\User::where('id', '!=', auth()->id())->get();
    return view('admin.chat.users', compact('users'));
})->name('chat');

Route::get('/chat/{user}', [ChatController::class, 'index'])->name('chat.index');
Route::post('/chat/send', [ChatController::class, 'store'])->name('chat.send');



});


Route::middleware(['auth', 'role:agent'])->prefix('agent')->name('agent.')->group(function () { 
    Route::get('/dashboard', [AgentDashboardController::class, 'index'])->name('dashboard');
    Route::resource('swaps', AgentSwapController::class);
    Route::get('/swap-history', [AgentSwapHistoryController::class, 'index'])->name('swap-history');

    //Batteries Deliveries
    Route::prefix('deliveries')->name('deliveries.')->group(function () {
        Route::get('/', [AgentBatteryDeliveryController::class, 'index'])->name('index');
        Route::post('/{delivery}/receive', [AgentBatteryDeliveryController::class, 'receive'])->name('receive');
        Route::post('/accept-multiple', [AgentBatteryDeliveryController::class, 'acceptMultiple'])->name('acceptMultiple');

    });

    // ✅ Add this inside
    Route::get('/agent/api/rider-last-battery/{rider}', function ($riderId) {
    $battery = \App\Models\Battery::where('current_rider_id', $riderId)
        ->where('status', 'in_use')
        ->latest('updated_at') // Optional, ensures latest update if multiple
        ->first();

    if ($battery) {
        return response()->json([
            'id' => $battery->id,
            'serial_number' => $battery->serial_number,
        ]);
    }

    return response()->json(null);
});

    Route::get('/chat', [ChatController::class, 'users'])->name('chat.users');
    Route::get('/chat/{user}', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [ChatController::class, 'store'])->name('chat.send');


});

Route::middleware(['auth', 'role:rider'])->prefix('rider')->name('rider.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Rider\RiderDashboardController::class, 'index'])->name('dashboard');
    Route::get('/swaps', [\App\Http\Controllers\Rider\RiderSwapController::class, 'index'])->name('swaps');
    Route::get('/profile', [RiderProfileController::class, 'show'])->name('profile');
    Route::get('/payments', [App\Http\Controllers\Rider\RiderPaymentController::class, 'index'])->name('payments.index');
    Route::get('/rider/payments/download', [\App\Http\Controllers\Rider\RiderPaymentController::class, 'download'])->name('payments.download');
    
    Route::get('/stations', function () {
        $stations = Station::all();
        return view('rider.stations.stations', compact('stations'));
    })->name('stations');

    Route::get('/chat', function () {
    $users = \App\Models\User::where('id', '!=', auth()->id())->get();
    return view('rider.chat.users', compact('users'));
})->name('chat');

    Route::get('/chat/{user}', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [ChatController::class, 'store'])->name('chat.send');

});


Route::middleware(['auth', 'role:finance'])->prefix('finance')->name('finance.')->group(function () {
    Route::get('/dashboard', [FinanceDashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports', [FinanceReportController::class, 'index'])->name('reports');
    Route::get('/reports/download/{type}', [FinanceReportController::class, 'download'])->name('reports.download');
    Route::get('/payments', [FinancePaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [FinancePaymentController::class, 'show'])->name('payments.show');
    Route::get('/purchases', [FinancePurchaseController::class, 'index'])->name('purchases.index');
    Route::get('purchases/{purchase}', [FinancePurchaseController::class, 'show'])->name('purchases.show');
    Route::get('/overdue', [FinanceOverdueController::class, 'index'])->name('overdue.index');
    Route::get('/overdue/export', [FinanceOverdueController::class, 'exportCsv'])->name('overdue.export');
    Route::post('/followup/mark/{purchase}', [\App\Http\Controllers\Finance\FinanceOverdueController::class, 'markAsContacted'])->name('followup.mark');
    Route::get('/followup/history/{purchase}', [FollowUpController::class, 'history'])->name('followup.history');


    Route::get('/chat', [ChatController::class, 'users'])->name('chat.users');
    Route::get('/chat/{user}', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [ChatController::class, 'store'])->name('chat.send');

});


// Profile & Pesapal
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::match(['post', 'get'], '/pesapal/ipn', [PesapalController::class, 'handleIPN'])->name('pesapal.ipn');



// Override default /login route from Laravel Breeze or UI
Route::get('/login', function () {
    return redirect()->route('rider.login');
})->name('login'); // ✅ Fixes the error
