<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UndispatchedController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\ArtisanCallsController;
use App\Http\Controllers\CsvController;

/*
|--------------------------------------------------------------------------
| If you receive the following message:
|--------------------------------------------------------------------------
|
| WARN  Failed to listen on 127.0.0.1:8000 (reason: Address already in use).
| INFO  Server running on [http://127.0.0.1:8001].
| 
| Run the following to find the PID running port 8000: $ lsof -i :8000
| 
| Eg.
| COMMAND   PID  USER   FD   TYPE DEVICE SIZE/OFF NODE NAME
| php8.1  18226 david   6u   IPv4 115269      0t0  TCP localhost:8000 (LISTEN)
| 
| The following will stop the server:
| $ sudo kill -9 18226
|
*/

// cd /opt/lampp/htdocs/LVL_FESP && php artisan serve

// http://127.0.0.1:8000/products
// http://127.0.0.1:8000/products/?page=2&limit=20
Route::match(['get', 'post'], 'products', [ProductsController::class, 'products'])->name('products');

// http://127.0.0.1:8000/undispatched
Route::get('undispatched', [UndispatchedController::class, 'index'])->name('undispatched');

Route::post('ajax/modifyDb', [AjaxController::class, 'modifyDb'])->name('ajax.modifyDb');

// http://127.0.0.1:8000/exportCsv
Route::get('exportCsv', [CsvController::class, 'exportCsv'])->name('exportCsv');

// http://127.0.0.1:8000/importCsv
Route::post('importCsv', [CsvController::class, 'importCsv'])->name('importCsv');

// http://127.0.0.1:8000/artisan/run_migrate_fresh__seed
Route::get('artisan/run_migrate_fresh__seed', [ArtisanCallsController::class, 'runMigrateFreshSeed'])->name('runMigrateFreshSeed');
