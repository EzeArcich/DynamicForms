<?php

use App\Http\Controllers\FormController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\FieldValueController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('form', FormController::class);
Route::resource('field', FieldController::class);
Route::get('getFormsData', [FormController::class, 'getFormsData']);
Route::post('storeValues', [FieldValueController::class, 'storeValues']);
Route::get('getFormDeletes', [FormController::class, 'getFormDeletes']);
Route::get('formDeletes', [FormController::class, 'formDeletes']);
Route::put('restoreFormDeletes/{id?}', [FormController::class, 'restoreFormDeletes']);
Route::get('formsList', [FormController::class, 'formsList']);
Route::get('getTableInfo', [FormController::class, 'getTableInfo']);



