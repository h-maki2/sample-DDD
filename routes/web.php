<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OrganizationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/detail', [OrganizationController::class, 'detail'])->name('organization.detail');
Route::post('/create', [OrganizationController::class, 'create']);
Route::post('/assign', [OrganizationController::class, 'assign']);
Route::post('/abolition', [OrganizationController::class, 'abolition']);