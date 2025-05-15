<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasarController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\SyncController;
use App\Exports\PriceExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\ExportController;
use Illuminate\Http\Request;
use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Http\Middleware\PreventBack;


// Ubah rute root ke HomeController
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/Dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('market', MarketController::class);
Route::resource('categories', CategoryController::class);

Route::get('/pasar', [PasarController::class, 'index'])->name('pasar.index');
Route::get('/pasar/create', [PasarController::class, 'create'])->name('pasar.create');
Route::post('/pasar', [PasarController::class, 'store'])->name('pasar.store');
Route::get('/pasar/{id}/edit', [PasarController::class, 'edit'])->name('pasar.edit');
Route::put('/pasar/{id}', [PasarController::class, 'update'])->name('pasar.update');
Route::delete('/pasar/{id}', [PasarController::class, 'destroy'])->name('pasar.destroy');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/market/{id}/location', [MarketController::class, 'editLocation'])->name('market.location');
Route::post('/market/{id}/location', [MarketController::class, 'updateLocation'])->name('market.location.update');
Route::put('/markets/{id}/location', [MarketController::class, 'editLocation']);

// rute Tampilan Harga dari Dashboard untuk Home page
Route::get('/', [HomeController::class, 'index']);


Route::get('/markets', function () {
    $response = Http::get('http://127.0.0.1:8081/markets'); // Pastikan ini benar
    return view('markets.index', ['markets' => $response->json()]);
});
Route::get('/market', [MarketController::class, 'index'])->name('market.index');

// Rute home sudah diubah ke root
// Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/officers', function () {
    return view('officers.index');
});
Route::patch('/officers/{id}/toggle', [OfficerController::class, 'toggleActive']);
Route::get('/officers/create', [OfficerController::class, 'create'])->name('officers.create');
Route::get('/officers/{id}/edit', [OfficerController::class, 'edit'])->name('officers.edit');
Route::resource('officers', OfficerController::class);
Route::get('/{officers_id}', [PriceController::class, 'showofficers'])->name('officers.show');
Route::get('/officers/sync', [OfficerController::class, 'syncOfficers'])->name('officers.sync');
Route::delete('/officers/{id}', [OfficerController::class, 'destroy'])->name('officers.destroy');
Route::patch('/officers/{id}/toggle', [OfficerController::class, 'toggleStatus'])->name('officers.toggle');
Route::delete('/officers/{id}', [OfficerController::class, 'destroy'])->name('officers.destroy');



Route::prefix('data-harga/prices')->name('prices.')->group(function () {
    Route::get('/', [PriceController::class, 'index'])->name('index');
    Route::get('/edit/{id}', [PriceController::class, 'edit'])->name('edit'); // ⬅ pindahkan ke atas
    Route::put('/update/{id}', [PriceController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [PriceController::class, 'destroy'])->name('destroy');
    Route::get('/data-harga/prices/{market_id}', [PriceController::class, 'showCategories'])->name('prices.categories');
    Route::get('/{market_id}', [PriceController::class, 'showCategories'])->name('categories');

    Route::get('/{market_id}', [PriceController::class, 'showCategories'])->name('categories');
    Route::get('/{market_id}/{category_id}', [PriceController::class, 'showPrices'])->name('items');
    Route::get('/create/{market_id}/{category_id}', [PriceController::class, 'create'])->name('create');
    Route::post('/store', [PriceController::class, 'store'])->name('store');
});

Route::get('/export/prices/{market_id}/{category_id}', function ($market_id, $category_id) {
    return Excel::download(new PriceExport($market_id, $category_id), 'harga_barang.xlsx');
})->name('prices.export');



Route::get('/export/pdf/prices/{market_id}/{category_id}', [ExportController::class, 'exportPDF'])->name('prices.export.pdf');
Route::get('/export/prices/{market_id}/{category_id}', function ($market_id, $category_id, Request $request) {
    return Excel::download(new PriceExport($market_id, $category_id, $request), 'harga_barang.xlsx');
})->name('prices.export');
Route::get('/data-harga/prices/{market_id}/{category_id}', [PriceController::class, 'showPrices'])->name('prices.items');

Route::get('/export/prices/{market_id}/{category_id}', function ($market_id, $category_id, Request $request) {
    $params = [
        'market_id' => $market_id,
        'category_id' => $category_id,
    ];

    if ($request->filled('start_date')) {
        $params['start_date'] = $request->start_date;
    }
    if ($request->filled('end_date')) {
        $params['end_date'] = $request->end_date;
    }

    // Ambil data harga dari API Golang
    $response = Http::get(env('API_BASE_URL') . '/prices', $params);
    $prices = $response->successful() ? $response->json() : [];

    // Buat file CSV di storage
    $filePath = storage_path('app/public/harga_barang.csv');

    SimpleExcelWriter::create($filePath)
        ->addRows(collect($prices)->map(function ($price) {
            return [
                'Nama Barang' => $price['item_name'],
                'Harga Awal' => $price['initial_price'],
                'Harga Sekarang' => $price['current_price'],
                'Persentase Perubahan' => round($price['change_percent'], 2) . '%',
                'Alasan' => $price['reason'],
                'Tanggal Diperbarui' => $price['updated_at'],
            ];
        }));

    return response()->download($filePath)->deleteFileAfterSend();
})->name('prices.export');

Route::get('/price-trend/{item_id}', [PriceController::class, 'showChart'])->name('prices.chart');

Route::get('/prices/chart/category/{id}', [PriceController::class, 'showCategoryChart'])->name('prices.chart.category');


// // PreventBackHistory Middleware
 Route::middleware(['auth', 'prevent-back'])->group(function(){
 Route::get('/home', [HomeController::class, 'index'])->name('home');
 });

 Route::get('/prices/market/{market_id}/categories', [PriceController::class, 'showCategories'])->name('prices.categories');

// Add new sync route
Route::get('/sync-data', [SyncController::class, 'syncData'])->name('sync.data');