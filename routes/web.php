<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\ProfesorController;
use App\Http\Controllers\EstadoAcademicoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\SuperAdmin\BedelesController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rutas de autenticación generadas por Breeze
require __DIR__ . '/auth.php';

// Rutas protegidas (solo usuarios autenticados con rol superadmin o bedel)
Route::middleware(['auth', 'verified', 'check.role:superadmin,bedel'])->group(function () {

    /**
     * Alias para dashboard -> panel
     */
    Route::get('/dashboard', function () {
        return redirect()->route('panel');
    })->name('dashboard');

    // Panel Principal
    Route::get('/panel', [PanelController::class, 'index'])->name('panel');

    /*
    |--------------------------------------------------------------------------
    | Módulo Asistencia
    |--------------------------------------------------------------------------
    */
    Route::prefix('asistencias')->name('asistencias.')->group(function () {
        Route::get('/', [AsistenciaController::class, 'index'])->name('index');

        // Armar cursada (GET: pantalla)
        Route::get('/armar-cursada', [AsistenciaController::class, 'armarCursada'])
            ->name('armar-cursada');

        // NUEVOS: acciones de armar cursada (sin Livewire)
        Route::post('/armar-cursada/add', [AsistenciaController::class, 'armarCursadaAdd'])
            ->name('armar-cursada.add');

        Route::post('/armar-cursada/remove', [AsistenciaController::class, 'armarCursadaRemove'])
            ->name('armar-cursada.remove');

        Route::get('/registros', [AsistenciaController::class, 'registros'])->name('registros');
        Route::get('/reportes', [AsistenciaController::class, 'reportes'])->name('reportes');
    });

    /*
    |--------------------------------------------------------------------------
    | Módulo Alumnos
    |--------------------------------------------------------------------------
    */
    Route::prefix('alumnos')->name('alumnos.')->group(function () {
        Route::get('/', [AlumnoController::class, 'index'])->name('index');
        Route::get('/nuevo', [AlumnoController::class, 'create'])->name('create');
        Route::post('/nuevo', [AlumnoController::class, 'store'])->name('store');

        // Estado académico (vista de lectura por alumno)
        Route::get('/{id}/estado', [AlumnoController::class, 'estadoAcademico'])->name('estado');

        // Cargar / editar estado académico de un alumno
        Route::get('/{id}/estado/editar', [AlumnoController::class, 'editarEstado'])->name('estado.editar');
        Route::post('/{id}/estado/guardar', [AlumnoController::class, 'guardarEstado'])->name('estado.guardar');

        // Edición de datos básicos del alumno
        Route::get('/{id}/edit', [AlumnoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AlumnoController::class, 'update'])->name('update');
    });

    /*
    |--------------------------------------------------------------------------
    | Módulo Profesores
    |--------------------------------------------------------------------------
    */
    Route::prefix('profesores')->name('profesores.')->group(function () {
        Route::get('/', [ProfesorController::class, 'index'])->name('index');
        Route::get('/listado', [ProfesorController::class, 'listado'])->name('listado');
        Route::get('/nuevo', [ProfesorController::class, 'create'])->name('create');
        Route::post('/nuevo', [ProfesorController::class, 'store'])->name('store');
        Route::get('/editar', [ProfesorController::class, 'buscar'])->name('buscar');
        Route::get('/editar/{id}', [ProfesorController::class, 'edit'])->name('edit');
        Route::put('/editar/{id}', [ProfesorController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProfesorController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Módulo Estados Académicos (vista general)
    |--------------------------------------------------------------------------
    */
    Route::get('/estado-academico', [EstadoAcademicoController::class, 'index'])
        ->name('estado-academico.index');

    /*
    |--------------------------------------------------------------------------
    | Módulo Perfil
    |--------------------------------------------------------------------------
    */
    Route::prefix('perfil')->name('perfil.')->group(function () {
        Route::get('/', [PerfilController::class, 'show'])->name('show');
        Route::get('/editar', [PerfilController::class, 'edit'])->name('edit');
        Route::put('/editar', [PerfilController::class, 'update'])->name('update');
    });

    /*
    |--------------------------------------------------------------------------
    | Super Admin - Gestión de Bedeles
    |--------------------------------------------------------------------------
    */
    Route::prefix('super-admin')
        ->middleware('check.role:superadmin')
        ->name('superadmin.')
        ->group(function () {
            Route::resource('bedeles', BedelesController::class);
        });
});
