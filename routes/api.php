<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LemburController;
use App\Http\Controllers\DetaillemburController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\LaporanController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout']);
Route::get('me', [AuthController::class, 'me']);

Route::post('lembur', [LemburController::class, 'store']); 
Route::get('lembur', [LemburController::class, 'index']); 

Route::post('detail-lembur', [DetaillemburController::class, 'store']); 
Route::put('detail-lembur', [DetaillemburController::class, 'update']); 

Route::get('departemen', [DepartemenController::class, 'index']); 
Route::post('departemen', [DepartemenController::class, 'store']); 

Route::get('laporan/{date}', [LaporanController::class, 'show']); 

Route::get('bulan', [LaporanController::class, 'index']); 