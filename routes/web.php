<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('inicio');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Ruta solo para administradores (id_rol = 1)
Route::get('/admin-test', function () {
    return '<h1>¡Hola Administrador! 🎉</h1><p>Solo tú puedes ver esto.</p>';
})->middleware(['auth', 'App\Http\Middleware\CheckRole:1'])->name('admin.test');

// Ruta solo para profesores (id_rol = 3)  
Route::get('/profesor-test', function () {
    return '<h1>¡Hola Profesor! 📚</h1><p>Solo profesores pueden ver esto.</p>';
})->middleware(['auth', 'App\Http\Middleware\CheckRole:3'])->name('profesor.test');

// RUTAS DE ASISTENCIA
Route::middleware(['auth'])->group(function () {
    // Solo para administradores y secretarias (roles 1 y 2)
    Route::get('/admin/reportes', [App\Http\Controllers\AsistenciaController::class, 'asistenciasDelDia'])->middleware(['App\Http\Middleware\CheckRole:1,2'])->name('asistencia.dia');
    Route::get('/admin/registro-manual', [App\Http\Controllers\AsistenciaController::class, 'registroManual'])->middleware(['App\Http\Middleware\CheckRole:1,2'])->name('asistencia.manual');
    Route::post('/asistencia/manual', [App\Http\Controllers\AsistenciaController::class, 'guardarRegistroManual'])->middleware(['App\Http\Middleware\CheckRole:1,2'])->name('asistencia.guardar-manual');
    Route::get('/asistencia/fecha/{fecha}', [App\Http\Controllers\AsistenciaController::class, 'asistenciasPorFecha'])->middleware(['App\Http\Middleware\CheckRole:1,2'])->name('asistencia.fecha');
});

// Nueva ruta para ver logs de asistencia
Route::get('/admin/logs', [App\Http\Controllers\LogsController::class, 'index'])->middleware(['auth', 'App\Http\Middleware\CheckRole:1'])->name('logs.asistencia');

// RUTAS PÚBLICAS DEL BIOMÉTRICO (SIN autenticación)
Route::get('/biometrico', [App\Http\Controllers\AsistenciaController::class, 'simuladorPublico'])->name('biometrico.publico');
Route::post('/biometrico/marcar', [App\Http\Controllers\AsistenciaController::class, 'marcarAsistencia'])->name('biometrico.marcar');

require __DIR__.'/auth.php';
