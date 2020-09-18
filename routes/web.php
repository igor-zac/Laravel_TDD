<?php

use App\Http\Controllers\DonationController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

use \App\Http\Controllers\ProjectController;

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
    return view('welcome');
});

//Projects
Route::resource('projects', ProjectController::class);

//Donations
Route::resource('donations', DonationController::class)->only([
    'index'
]);
Route::resource('projects.donations', DonationController::class)->shallow()->only([
    'create', 'store', 'show'
]);

Route::post('/pay/{donation}', [PaymentController::class, 'pay'])->name('pay');

//Authentication
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
