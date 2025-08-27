<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\RecepcionController;



Route::get('/dashboard', [InventoryController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/inventories', [InventoryController::class, 'index'])->name('inventories.index');

    Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/{id}', [PedidoController::class, 'show'])->name('pedidos.show');

    Route::get('/recepciones', [RecepcionController::class, 'index'])->name('recepciones.index');
});


require __DIR__.'/auth.php';
