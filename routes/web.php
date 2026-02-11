<?php

use App\Http\Controllers\PDFController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CentroController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VehiculoController;



/*
 |--------------------------------------------------------------------------
 | Rutas públicas (SIN autenticación)
 |--------------------------------------------------------------------------
 */
Auth::routes();
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

/*
 |--------------------------------------------------------------------------
 | Rutas protegidas (CON autenticación)
 |--------------------------------------------------------------------------
 */
Route::middleware(['auth'])->group(function () {

    // Usuarios registrados pueden entrar a estas vistas
    Route::get('/', function () {
        return view('welcome');
    }
    )->name('welcome');

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    //Crud users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');

    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    //Crud departamentos
    Route::get('/departamentos', [DepartamentoController::class, 'index'])->name('departamentos.index');

    Route::get('/departamentos/create', [DepartamentoController::class, 'create'])->name('departamentos.create');
    Route::post('departamentos', [DepartamentoController::class, 'store'])->name('departamentos.store');

    Route::get('/departamentos/{departamento}', [DepartamentoController::class, 'show'])->name('departamentos.show');

    Route::get('/departamentos/{departamento}/edit', [DepartamentoController::class, 'edit'])->name('departamentos.edit');
    Route::put('/departamentos/{departamento}', [DepartamentoController::class, 'update'])->name('departamentos.update');

    Route::delete('/departamentos/{departamento}', [DepartamentoController::class, 'destroy'])->name('departamentos.destroy');

    //Crud centros
    Route::get('/centros', [CentroController::class, 'index'])->name('centros.index');

    Route::get('/centros/create', [CentroController::class, 'create'])->name('centros.create');
    Route::post('/centros', [CentroController::class, 'store'])->name('centros.store');

    Route::get('/centros/{centro}', [CentroController::class, 'show'])->name('centros.show');

    Route::get('centros/{centro}/edit', [CentroController::class, 'edit'])->name('centro.edit');
    Route::put('/centros/{centro}', [CentroController::class, 'update'])->name('centros.update');

    Route::delete('/centros/{centro}', [CentroController::class, 'destroy'])->name('centros.destroy');

    //Crud clientes
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');

    Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');

    Route::get('/clientes/{cliente}', [ClienteController::class, 'show'])->name('clientes.show');

    Route::get('/clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
    Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');

    Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');

    //Crud vehiculos
    Route::get('/vehiculos', [VehiculoController::class, 'index'])->name('vehiculos.index');

    Route::get('/vehiculos/create', [VehiculoController::class, 'create'])->name('vehiculos.create');
    Route::post('/vehiculos', [VehiculoController::class, 'store'])->name('vehiculos.store');
    Route::get('/vehiculos/export', [VehiculoController::class, 'export'])->name('vehiculos.export');
    Route::get('/vehiculos/pdf', [VehiculoController::class, 'exportPdf'])->name('vehiculos.pdf');

    Route::get('/vehiculos/{vehiculo}', [VehiculoController::class, 'show'])->name('vehiculos.show');

    Route::get('/vehiculos/{vehiculo}/edit', [VehiculoController::class, 'edit'])->name('vehiculos.edit');
    Route::put('/vehiculos/{vehiculo}', [VehiculoController::class, 'update'])->name('vehiculos.update');

    Route::delete('/vehiculos/{vehiculo}', [VehiculoController::class, 'destroy'])->name('vehiculos.destroy');

    // PDF
    Route::get('/pdf', [PDFController::class, 'index'])->name('pdf.index');
    Route::post('/pdf/upload', [PDFController::class, 'upload'])->name('pdf.upload');

    Route::post('/pdf/procesar', [PDFController::class, 'procesarPdf'])->name('pdf.procesar');

    // Ofertas
    Route::resource('ofertas', App\Http\Controllers\OfertaController::class)->only(['index', 'show']);

    /*
 |--------------------------------------------------------------------------
 | 🔒 SOLO ADMIN puede gestionar departamentos, centros y roles
 |--------------------------------------------------------------------------
 */
    Route::middleware(['is_admin'])->group(function () {
        //Crud de roles
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');

        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');

        Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');

        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');

        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    }
    );
});