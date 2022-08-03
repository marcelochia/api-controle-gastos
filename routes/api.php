<?php

use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\IncomesController;
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

Route::apiResource('receitas', IncomesController::class)->names('income')->parameters(['receitas' => 'income']);
Route::apiResource('despesas', ExpensesController::class)->names('expense')->parameters(['despesas' => 'expense']);