<?php

use App\Http\Controllers\AccountsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/accounts', function () {
    return view("accounts.index");
});
Route::get("/accounts/create", function() {
    return view("accounts.create");
});


Route::get("/api/accounts", [AccountsController::class, "getAccounts"]);
Route::delete("/api/accounts", [AccountsController::class, "deleteAccount"]);

Route::get("/api/accounts/qr", [AccountsController::class, "getQr"]);
Route::get("/api/accounts/isLogged", [AccountsController::class, "isLogged"]);

