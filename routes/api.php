<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\CatController;

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

Route::post('admin/login', [AdminAuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('admin/user', function(Request $request) {
    return $request->user();
});
Route::post('/admin/logout', [AdminAuthController::class, 'logout']);

// CRUD routes for Cats - Protected by admin and auth middleware
Route::middleware(['auth:sanctum', 'admin'])->group(function() {
    Route::post('cats', [CatController::class, 'store']);
    Route::get('cats', [CatController::class, 'index']);
    Route::get('cats/{cat}', [CatController::class, 'show']);
    Route::put('cats/{cat}', [CatController::class, 'update']);
    Route::delete('cats/{cat}', [CatController::class, 'destroy']);
});
