<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/get_all_invoices',[\App\Http\Controllers\InvoiceController::class , 'get_all_invoice']);
Route::get('/search_invoice',[\App\Http\Controllers\InvoiceController::class , 'search_invoice']);
Route::get('/create_invoice',[\App\Http\Controllers\InvoiceController::class , 'create_invoice']);
Route::get('/customers',[\App\Http\Controllers\CustomerController::class , 'customers']);
Route::get('/products',[\App\Http\Controllers\ProductController::class , 'products']);
Route::post('/add_invoice',[\App\Http\Controllers\InvoiceController::class , 'add_invoice']);
Route::get('/show_invoice/{id}',[\App\Http\Controllers\InvoiceController::class , 'show_invoice']);
Route::get('/edit_invoice/{id}',[\App\Http\Controllers\InvoiceController::class , 'edit_invoice']);
Route::get('/delete_invoice_items/{id}',[\App\Http\Controllers\InvoiceController::class , 'delete_invoice_items']);
Route::post('/update_invoice/{id}',[\App\Http\Controllers\InvoiceController::class , 'update_invoice']);
