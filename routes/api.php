<?php

use App\Http\Controllers\BB40Controller;
use App\Http\Controllers\MMP40Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('BB40',[BB40Controller::class],'index');
Route::get('/BB40', [BB40Controller::class, 'index']);
Route::get('/MMP40', [MMP40Controller::class, 'index']);