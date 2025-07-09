<?php

use App\Models\ErrorValidacion;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ValidacionController;
use App\Http\Controllers\ProductImportController;
use App\Http\Controllers\PedidoImportController;
use App\Http\Controllers\RegistroErrorValidacionController;



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('products/import', [ProductImportController::class, 'show'])->name('products.import.form');
    Route::post('products/import', [ProductImportController::class, 'import'])->name('products.import');
    Route::get('pedidos/import', [PedidoImportController::class, 'show'])->name('pedidos.import.form');
    Route::post('pedidos/import', [PedidoImportController::class, 'import'])->name('pedidos.import');
    Route::resource('pedidos', PedidoController::class)->except('index');
    Route::resource('products', ProductController::class);
    Route::resource('clientes', ClienteController::class);
    Route::resource('users', UserController::class);
    Route::get('pedidos/assign/{pedido}', [PedidoController::class,'assign'])->name('pedidos.assign');
    Route::put('pedidos/assign/{pedido}', [PedidoController::class, 'updateAssign'])->name('pedidos.updateAssign');
    Route::get('pedidos/send/{pedido}', [PedidoController::class,'upEstado'])->name('pedidos.send');
    Route::patch('pedidos/{pedido}/reasignar', [PedidoController::class, 'reasignar'])->name('pedidos.reasignar');
    Route::patch('pedidos/{pedido}/quitar-usuario', [PedidoController::class, 'quitarUsuario'])->name('pedidos.quitarUsuario');
    Route::get('errores-validacion', [RegistroErrorValidacionController::class, 'index'])->name('errores.index');
});

Route::middleware(['auth', 'role:admin,user'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {return view('dashboard');});
    Route::get('/validacion', [ValidacionController::class, 'index'])->name('validacion.index');
    Route::get('/validacion/{pedido}', [ValidacionController::class, 'validar'])->name('validacion.validar');
    Route::post('/validacion/{pedido}/producto', [ValidacionController::class, 'validarProducto'])->name('validacion.producto');
    //Route::post('/validacion/{pedido}', [ValidacionController::class, 'procesar'])->name('validacion.procesar');
    Route::patch('/validacion/{pedido}/finalizar', [ValidacionController::class, 'finalizar'])->name('validacion.finalizar');
        Route::post('/seleccionar-cliente', [ClienteController::class, 'seleccionar'])->name('cliente.seleccionar');
    Route::put('/pedidos/{pedido}/finalizar', [PedidoController::class, 'finalizar'])->name('pedidos.finalizar');
});

require __DIR__.'/auth.php';
