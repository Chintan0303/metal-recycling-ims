<?php

use App\Http\Middleware\AdminMiddleWare;
use App\Livewire\AdvancedProcessingList;
use App\Livewire\AdvancedprocessingView;
use App\Livewire\BasicProcessingList;
use App\Livewire\BasicProcessingView;
use App\Livewire\CustomerList;
use App\Livewire\ProductsList;
use App\Livewire\PurchaseList;
use App\Livewire\PurchaseView;
use App\Livewire\SalesList;
use App\Livewire\ScrapProductsList;
use App\Livewire\VendorList;
use App\Livewire\VendorView;
use Illuminate\Support\Facades\Route;

// Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {
    Route::get('/vendors', VendorList::class)->name('vendors');
    Route::get('/vendors/{vendor}', VendorView::class)->name('vendors.view');
    Route::get('/scrap-products', ScrapProductsList::class)->name('scrap-products');
    Route::get('/purchases', PurchaseList::class)->name('purchases');
    Route::get('/purchases/{purchase}', PurchaseView::class)->name('purchases.view');
    Route::get('/basic-processings', BasicProcessingList::class)->name('basic-processings');
    Route::get('/basic-processings/{id}', BasicProcessingView::class)->name('basic-processings.view');
    Route::get('/advanced-processing', AdvancedProcessingList::class)->name('advanced-processings');
    Route::get('/advanced-processing/{id}', AdvancedprocessingView::class)->name('advanced-processings.view');

    Route::middleware([AdminMiddleWare::class])->group(function () {
        Route::view('dashboard', 'dashboard')->name('dashboard');
        Route::view('profile', 'profile')->name('profile');
        Route::view('users', 'users')->name('users');
        Route::get('/customers', CustomerList::class)->name('customers');
        Route::get('/products', ProductsList::class)->name('products');
        Route::get('/sales', SalesList::class)->name('sales');
    });
});
require __DIR__.'/auth.php';
