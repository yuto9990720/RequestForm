<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;
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

Route::get('/', [ContactController::class, 'index'])->name('contacts.index');
Route::post('/confirm', [ContactController::class,'confirm'])->name('contacts.confirm');
Route::post('/store', [ContactController::class,'store'])->name('contacts.store');
Route::get('/thanks', [ContactController::class,'thanks'])->name('contacts.thanks');
Route::post('/contact/back', [ContactController::class, 'back'])->name('contacts.back');
Route::middleware('auth')->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin');
Route::get('/admin/export', [AdminController::class, 'export'])->name('admin.export');    
Route::get('/admin/contacts/{contact}', [AdminController::class, 'show'])
        ->name('admin.contacts.show');
 Route::delete('/admin/contacts/{contact}', [AdminController::class, 'destroy'])
        ->name('admin.contacts.destroy');
});