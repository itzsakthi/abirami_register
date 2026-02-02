<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\YelamController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('Allreport', [YelamController::class, 'reportdata'])->name('reportdata');
Route::post('pullisearch', [YelamController::class, 'pullisearch'])->name('pullisearch');
Route::post('donsearch', [YelamController::class, 'donsearch'])->name('donsearch');
Route::post('expensesearch', [YelamController::class, 'expensesearch'])->name('expensesearch');
Route::post('yellamsearch', [YelamController::class, 'yellamsearch'])->name('yellamsearch');
