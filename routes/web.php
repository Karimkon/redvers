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
use App\Http\Controllers\Agent\SwapPromotionController;
use App\Http\Controllers\Agent\AgentMotorcycleDailyPaymentController;
use App\Http\Controllers\Inventory\PartController;
use App\Http\Controllers\Inventory\StockEntryController;
use App\Http\Controllers\Inventory\SaleController;
use App\Http\Controllers\Admin\SpareShopDashboardController;
use App\Http\Controllers\Admin\LowStockAlertController;
use App\Http\Controllers\Admin\ShopAnalyticsController;
use \App\Http\Controllers\Admin\InventoryOperatorController;
use \App\Http\Controllers\Admin\ShopController;
use \App\Http\Controllers\Admin\AdminPartController;
use \App\Http\Controllers\Admin\FinanceController;
use \App\Http\Controllers\Admin\AdminWalletController;
use \App\Http\Controllers\Admin\AdminMaintenanceController;
use \App\Http\Controllers\Rider\RiderWalletController;
use \App\Http\Controllers\Admin\AdminSwapPromotionsController;
use App\Http\Controllers\Admin\MechanicController;
use App\Http\Controllers\Finance\RevenueController;
use App\Http\Controllers\Finance\ExpenditureController;
use App\Http\Controllers\Finance\COGSController;
use App\Http\Controllers\Finance\LoanController;
use App\Http\Controllers\Finance\InvestorController;
use App\Http\Controllers\Finance\DepreciationController;
use App\Http\Controllers\Finance\TaxSettingController;
use App\Http\Controllers\Finance\ProductController;
use App\Http\Controllers\Finance\ProductCategoryController;
use App\Http\Controllers\Finance\IncomeStatementController;
use App\Http\Controllers\Finance\BalanceSheetController;
use App\Models\Attachment;
use \App\Http\Controllers\Mechanic\MechanicDashboardController;
use \App\Http\Controllers\Mechanic\MechanicMaintenanceController;
use \App\Http\Controllers\Admin\AdminUserController;

// Home
Route::get('/', fn () => view('welcome'));
Route::get('/pesapal/request-token', [PesapalTokenTestController::class, 'getToken']);

// Login views per role
Route::get('/admin/login', fn () => view('auth.admin-login'))->name('admin.login');
Route::get('/agent/login', fn () => view('auth.agent-login'))->name('agent.login');
Route::get('/rider/login', fn () => view('auth.rider-login'))->name('rider.login');
Route::get('/finance/login', fn () => view('auth.finance-login'))->name('finance.login');
Route::get('/inventory/login', fn () => view('auth.inventory-login'))->name('inventory.login');
Route::get('/mechanic/login', fn () => view('auth.mechanic-login'))->name('mechanic.login');


Route::get('/pesapal/auth', [\App\Http\Controllers\PesapalController::class, 'authenticate'])->name('pesapal.auth');

Route::match(['GET', 'POST'], '/pesapal/callback', [PesapalController::class, 'handleCallback'])->name('pesapal.callback');

Route::match(['GET', 'POST'], '/pesapal/promo-callback', [PesapalController::class, 'handlePromotionCallback'])->name('pesapal.promo.callback');

Route::match(['GET', 'POST'], '/pesapal/callback/daily', [PesapalController::class, 'handleDailyPaymentCallback'])->name('pesapal.callback.daily');


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

// Inventory login submit
Route::post('/inventory/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember')) && Auth::user()->role === 'inventory') {
        $request->session()->regenerate();
        return redirect()->intended(route('inventory.dashboard'));
    }   

    Auth::logout();
    return redirect()->route('inventory.login')->with('error', 'Only inventory users can login here.');
})->name('inventory.login.submit');

Route::post('/mechanic/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember')) && Auth::user()->role === 'mechanic') {
        $request->session()->regenerate();
        return redirect()->intended(route('mechanic.maintenances.index'));
    }

    Auth::logout();
    return redirect()->route('mechanic.login')->with('error', 'Only mechanics can login here.');
})->name('mechanic.login.submit');


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
    Route::resource('admins', AdminUserController::class);
    Route::resource('stations', StationController::class);
    Route::resource('batteries', BatteryController::class);
    Route::resource('swaps', SwapController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('motorcycle-units', MotorcycleUnitController::class);
    Route::resource('finance', FinanceController::class);   
    Route::resource('mechanics', MechanicController::class);


    /**Swap Promotion Admin */
    Route::get('/promotions/payment', [AdminSwapPromotionsController::class, 'redirectToPayment'])
        ->name('promotions.pesapal');
    Route::post('/promotions/pay/pesapal', [AdminSwapPromotionsController::class, 'payViaPesapal'])
        ->name('promotions.pay.pesapal');
    Route::resource('promotions', AdminSwapPromotionsController::class); 

    /** -----------------------------------
     * 🔹 Motorcycle Purchase System
     * ---------------------------------- */
    Route::resource('purchases', MotorcyclePurchaseController::class);
    Route::post('/purchases/{purchase}/motorcycle-payments', [MotorcyclePaymentController::class, 'store'])
        ->name('motorcycle-payments.store');
    Route::delete('/purchases/{purchase}/motorcycle-payments/{payment}', [MotorcyclePaymentController::class, 'destroy'])
    ->name('motorcycle-payments.destroy');

    //Spare Shops
    Route::get('/spares/dashboard', [SpareShopDashboardController::class, 'index'])->name('spares.dashboard');
    Route::get('low-stock-alerts', [LowStockAlertController::class, 'index'])->name('low-stock-alerts.index');
    Route::post('low-stock-alerts/{alert}/resolve', [LowStockAlertController::class, 'resolve'])->name('low_stock_alerts.resolve');
    Route::resource('parts', AdminPartController::class)->names('parts');
    Route::get('/shops/{shop}/profit-details', [ShopAnalyticsController::class, 'profitDetails'])->name('shops.profit.details');

    Route::get('/shops/{shop}/expected-revenue',[ShopAnalyticsController::class, 'expectedRevenueDetails'])->name('shops.expected.revenue');
    // ── NEW: CSV export of chart data
    Route::get('shops/{shop}/analytics/export-summary', [ShopAnalyticsController::class, 'exportSummaryCsv'])->name('shops.analytics.exportSummary');

    // Shop Specific
    Route::get('shops', [ShopAnalyticsController::class, 'index'])->name('shops.index');
    Route::get('/shops/{shop}/analytics', [ShopAnalyticsController::class, 'show'])->name('shops.analytics');

    //Inventory Operators
    Route::resource('inventory', InventoryOperatorController::class)->names('inventory');
    // Shop CRUD routes
    Route::resource('shops', ShopController::class)->names('shops');


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

    Route::get('/notifications/unread', function () {
        $userId = auth()->id();

        $unreadMessages = \App\Models\Message::where('receiver_id', $userId)
            ->where('is_read', 0)
            ->latest()
            ->take(5)
            ->with('sender:id,name')
            ->get();

        return response()->json([
            'count' => $unreadMessages->count(),
            'messages' => $unreadMessages->map(function ($msg) {
                return [
                    'sender' => $msg->sender->name,
                    'text' => \Str::limit($msg->message, 30),
                    'user_id' => $msg->sender->id,   // ✅ Add this
                    'id' => $msg->id   
                ];
            }),
        ]);
    })->name('notifications');


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

    // 🔍 Parts quick‑lookup (admin)
Route::get('/api/lookup', function (\Illuminate\Http\Request $request) {
    $q = $request->get('q', '');

    return \App\Models\Part::with('shop:id,name')           // eager‑load shop
        ->where('name', 'like', "%{$q}%")
        ->select('id', 'shop_id', 'name', 'stock', 'price') // include shop_id
        ->limit(10)
        ->get()
        ->map(function ($p) {                               // flatten for JS ease
            return [
                'id'    => $p->id,
                'name'  => $p->name,
                'stock' => $p->stock,
                'price' => $p->price,
                'shop'  => $p->shop->name ?? '—',
                'edit_url' => route('admin.parts.edit', ['part' => $p->id]),
            ];
        });
})->name('api.lookup');



    Route::get('/chat', function () {
    $users = \App\Models\User::where('id', '!=', auth()->id())->get();
    return view('admin.chat.users', compact('users'));
})->name('chat');

Route::get('/chat/{user}', [ChatController::class, 'index'])->name('chat.index');
Route::post('/chat/send', [ChatController::class, 'store'])->name('chat.send');


    /** -----------------------------------
     * 🔹 Wallet Management Routes
     * ---------------------------------- */
    Route::prefix('wallets')->name('wallets.')->group(function () {
    Route::get('/',               [AdminWalletController::class,'index'])->name('index');          // list wallets
    Route::get('/{user}/top-up',  [AdminWalletController::class,'topUpForm'])->name('topup');      // show form
    Route::post('/{user}/top-up', [AdminWalletController::class,'topUpStore'])->name('topup.store'); // process
    Route::get('/{user}',         [AdminWalletController::class,'show'])->name('show');            // ledger
});

Route::get('/maintenance/history', [AdminMaintenanceController::class, 'index'])->name('maintenance.index');
Route::get('/maintenance/{maintenance}', [AdminMaintenanceController::class, 'show'])->name('maintenance.show');


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

    Route::prefix('promotions')->name('promotions.')->group(function () {
    Route::get('/', [SwapPromotionController::class, 'index'])->name('index');
    Route::get('/create', [SwapPromotionController::class, 'create'])->name('create');
    Route::post('/store', [SwapPromotionController::class, 'store'])->name('store');
    Route::get('/payment', [SwapPromotionController::class, 'redirectToPayment'])->name('payment');
    // ✅ Add this for pesapal payment submission
    Route::post('/pay/pesapal', [SwapPromotionController::class, 'payViaPesapal'])->name('pay.pesapal');
    Route::get('/{promotion}/edit', [SwapPromotionController::class, 'edit'])->name('edit');
    Route::put('/{promotion}', [SwapPromotionController::class, 'update'])->name('update');
    Route::delete('/{promotion}', [SwapPromotionController::class, 'destroy'])->name('destroy');
});

    Route::get('/daily-payments/create', [AgentMotorcycleDailyPaymentController::class, 'create'])->name('daily-payments.create');
    Route::post('/daily-payments/store', [AgentMotorcycleDailyPaymentController::class, 'store'])->name('daily-payments.store');
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

    //Wallet
    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/', [RiderWalletController::class, 'index'])->name('index'); // /rider/wallet
        Route::get('/topup', [RiderWalletController::class, 'topUpForm'])->name('topup.form'); // /rider/wallet/topup
        Route::post('/topup', [RiderWalletController::class, 'initiateTopUp'])->name('topup.initiate');
        Route::get('/pesapal/callback', [RiderWalletController::class, 'pesapalCallback'])->name('pesapal.callback');
    });

    Route::get('/schedule', [\App\Http\Controllers\Rider\PaymentScheduleController::class, 'index'])->name('schedule');

     // Initiate daily payment
    Route::get('/daily-payment', [App\Http\Controllers\Rider\RiderDailyPaymentController::class, 'create'])
        ->name('daily-payment.create');

    // Submit payment request to Pesapal
    Route::post('/daily-payment/pay', [App\Http\Controllers\Rider\RiderDailyPaymentController::class, 'payViaPesapal'])
        ->name('daily-payment.pay');

    // Pesapal Callback handling
    Route::match(['GET', 'POST'], '/daily-payment/callback', [App\Http\Controllers\Rider\RiderDailyPaymentController::class, 'handleCallback'])
        ->name('daily-payment.callback');
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

    Route::resource('revenues', RevenueController::class);
    Route::resource('expenditures', ExpenditureController::class);
    Route::resource('cogs', COGSController::class);
    Route::resource('loans', LoanController::class);
    Route::resource('investors', InvestorController::class);
    Route::resource('depreciations', DepreciationController::class);
    Route::resource('taxes', TaxSettingController::class);
    Route::resource('products', ProductController::class);
    Route::resource('product_categories', ProductCategoryController::class);

    Route::resource('investors', App\Http\Controllers\Finance\InvestorController::class);

    // ✅ Add this for viewing attachments
    Route::get('/investors/attachment/{attachment}', [App\Http\Controllers\Finance\InvestorController::class, 'viewAttachment'])->name('investors.attachment');
    
    Route::get('/income-statement', [IncomeStatementController::class, 'index'])->name('income.index');
    Route::get('/income-statement/export/{format}', [IncomeStatementController::class, 'export'])->name('income.export');

    Route::get('/balance-sheet', [BalanceSheetController::class, 'index'])->name('balance.index');
    Route::get('/balance-sheet/export/{format}', [BalanceSheetController::class, 'export'])->name('balance.export');

    Route::get('/chat', [ChatController::class, 'users'])->name('chat.users');
    Route::get('/chat/{user}', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [ChatController::class, 'store'])->name('chat.send');

    Route::get('/purchases', [FinancePurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/purchases/{purchase}', [FinancePurchaseController::class, 'show'])->name('purchases.show');
    Route::post('/purchases/{purchase}/payments', [MotorcyclePaymentController::class, 'store'])->name('motorcycle-payments.store');
});

Route::middleware(['auth', 'role:inventory'])->prefix('inventory')->name('inventory.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Inventory\InventoryDashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', function () {
        return view('inventory.profile'); // Create this Blade view if needed
    })->name('profile');

    // Placeholder routes (coming next)
    Route::resource('parts', PartController::class);
    Route::resource('stock-entries', StockEntryController::class);
    Route::resource('sales', SaleController::class);

    //Parts price lookup
     Route::get('/api/lookup', function (Request $request) {
        $query = $request->get('q');
        $shop = Auth::user()->shop;

        return $shop->parts()
            ->where('name', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'stock', 'price']);
    })->name('api.lookup'); // ✅ this gives us inventory.api.lookup
        

});

Route::middleware(['auth', 'role:mechanic'])->prefix('mechanic')->name('mechanic.')->group(function () {
    Route::get('/dashboard', [MechanicDashboardController::class, 'index'])->name('dashboard');
    Route::resource('maintenances', MechanicMaintenanceController::class);
});



// Profile & Pesapal
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



// Override default /login route from Laravel Breeze or UI
Route::get('/login', function () {
    return redirect()->route('rider.login');
})->name('login'); // ✅ Fixes the error
