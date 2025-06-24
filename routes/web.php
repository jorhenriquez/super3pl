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
use App\Http\Controllers\RegistroErrorValidacionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
Route::middleware(['auth', CheckRole::class . ':admin'])->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});

Route::middleware(['auth', CheckRole::class . ':admin'])->group(function () {
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
    Route::get('/clientes/{cliente}', [ClienteController::class, 'show'])->name('clientes.show');
    Route::get('/clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
    Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
    Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
});
*/
// Temporalmente fuera del auth para probar

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('products/import', [ProductImportController::class, 'show'])->name('products.import.form');
    Route::post('/products/import', [ProductImportController::class, 'import'])->name('products.import');
    Route::resource('pedidos', PedidoController::class)->except('index');
    Route::resource('products', ProductController::class);
    Route::resource('clientes', ClienteController::class);
    Route::resource('users', UserController::class);
    Route::get('pedidos/assign/{pedido}', [PedidoController::class,'assign'])->name('pedidos.assign');
    Route::put('pedidos/assign/{pedido}', [PedidoController::class, 'updateAssign'])->name('pedidos.updateAssign');
    Route::get('pedidos/send/{pedido}', [PedidoController::class,'upEstado'])->name('pedidos.send');
    Route::patch('/pedidos/{pedido}/reasignar', [PedidoController::class, 'reasignar'])->name('pedidos.reasignar');
    Route::patch('/pedidos/{pedido}/quitar-usuario', [PedidoController::class, 'quitarUsuario'])->name('pedidos.quitarUsuario');
    Route::get('/errores-validacion', [RegistroErrorValidacionController::class, 'index'])->name('errores.index');
    Route::get('products/import', [ProductImportController::class, 'show'])->name('products.import.form');
    Route::post('/products/import', [ProductImportController::class, 'import'])->name('products.import');
});



/*
Route::middleware('auth')->group(function () {
    Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/create', [PedidoController::class, 'create'])->name('pedidos.create');
    Route::post('/pedidos', [PedidoController::class, 'store'])->name('pedidos.store');
    Route::get('/pedidos/{product}', [PedidoController::class, 'show'])->name('pedidos.show');
    Route::get('/pedidos/{product}/edit', [PedidoController::class, 'edit'])->name('pedidos.edit');
    Route::patch('/pedidos/{product}', [PedidoController::class, 'update'])->name('pedidos.update');
    Route::delete('/pedidos/{product}', [PedidoController::class, 'destroy'])->name('pedidos.destroy');
});
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/validacion', [ValidacionController::class, 'index'])->name('validacion.index');
    Route::get('/validacion/{pedido}', [ValidacionController::class, 'validar'])->name('validacion.validar');
    Route::post('/validacion/{pedido}/producto', [ValidacionController::class, 'validarProducto'])->name('validacion.producto');
    //Route::post('/validacion/{pedido}', [ValidacionController::class, 'procesar'])->name('validacion.procesar');
    Route::patch('/validacion/{pedido}/finalizar', [ValidacionController::class, 'finalizar'])->name('validacion.finalizar');

});


require __DIR__.'/auth.php';
