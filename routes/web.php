<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\Auth\RegisterController;


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
    })->name('welcome');
    
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


    // ✅ CUALQUIERA con una cuenta puede ver los listado
    Route::get('/users', [UserController::class, 'index'])
       ->middleware('can:viewAny,App\Models\User')
       ->name('users.index');

    // Departamentos
    Route::get('/departamentos', [DepartamentoController::class, 'index'])->name('departamentos.index');

    
    // 🔒 SOLO ADMIN puede gestionar usuarios
    Route::middleware(['role:admin'])->group(function () {
        
        //Crud usuarios
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');

        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        //Crud departamentos
        Route::get('/departamentos/create', [DepartamentoController::class, 'create'])->name('departamentos.create');
        Route::post('departamentos', [DepartamentoController::class, 'store'])->name('departamentos.store');

        Route::get('/departamentos/{departamento}/edit', [DepartamentoController::class, 'edit'])->name('departamentos.edit');
        Route::put('/departamentos/{departamento}', [DepartamentoController::class, 'update'])->name('departamentos.update');

        Route::delete('/departamentos/{departamento}', [DepartamentoController::class, 'destroy'])->name('departamentos.destroy');


    });
});