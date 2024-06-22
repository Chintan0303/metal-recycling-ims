<?php

use App\Livewire\AdvancedProcessingList;
use App\Livewire\AdvancedprocessingView;
use App\Livewire\BasicProcessingList;
use App\Livewire\BasicProcessingView;
use App\Livewire\CustomerList;
use App\Livewire\MaterialList;
use App\Livewire\ProcessedProductsList;
use App\Livewire\PurchaseList;
use App\Livewire\SaleList;
use App\Livewire\ScrapProductsList;
use App\Livewire\VendorList;
use Illuminate\Support\Facades\Route;

// Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');
    Route::view('users', 'users')->name('users');
    Route::get('/vendors', VendorList::class)->name('vendors');
    Route::get('/customers', CustomerList::class)->name('customers');
    Route::get('/materials', MaterialList::class)->name('materials');
    Route::get('/processed-products', ProcessedProductsList::class)->name('processed-products');
    Route::get('/scrap-products', ScrapProductsList::class)->name('scrap-products');
    Route::get('/purchases', PurchaseList::class)->name('purchases');
    Route::get('/sales', SaleList::class)->name('sales');
    Route::get('/basic-processings', BasicProcessingList::class)->name('basic-processings');
    Route::get('/basic-processings/{id}', BasicProcessingView::class)->name('basic-processings.view');
    Route::get('/advanced-processing', AdvancedProcessingList::class)->name('advanced-processings');
    Route::get('/advanced-processing/{id}', AdvancedprocessingView::class)->name('advanced-processings.view');

});
require __DIR__.'/auth.php';
