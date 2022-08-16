<?php

use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\IncomesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SummaryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('receitas', IncomesController::class)
        ->names('income')
        ->parameters(['receitas' => 'income']);

    Route::get('/receitas/{year}/{month}', [IncomesController::class, 'listByMonth']);

    Route::apiResource('despesas', ExpensesController::class)
        ->names('expense')
        ->parameters(['despesas' => 'expense']);

    Route::get('/despesas/{year}/{month}', [ExpensesController::class, 'listByMonth']);

    Route::get('/resumo/{year}/{month}', [SummaryController::class, 'summary'])
            ->whereNumber(['year', 'month']);
});
