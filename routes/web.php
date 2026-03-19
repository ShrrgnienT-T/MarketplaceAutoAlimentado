<?php

use App\Http\Controllers\Admin\ImportBatchController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.products.index');
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.products.index');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('admin')->name('admin.')->group(function (): void {
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');

        Route::get('/imports', [ImportBatchController::class, 'index'])->name('imports.index');
        Route::post('/imports', [ImportBatchController::class, 'store'])->name('imports.store');
        Route::get('/imports/{batch}', [ImportBatchController::class, 'show'])->name('imports.show');
        Route::post('/imports/{batch}/publish', [ImportBatchController::class, 'publish'])->name('imports.publish');

        Route::post('/imports/{batch}/items/{item}/approve', [ImportBatchController::class, 'approveItem'])
            ->name('imports.items.approve');
        Route::post('/imports/{batch}/items/{item}/reject', [ImportBatchController::class, 'rejectItem'])
            ->name('imports.items.reject');
    });
});

require __DIR__.'/auth.php';
