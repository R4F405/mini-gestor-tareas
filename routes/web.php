<?php

use Illuminate\Support\Facades\Route;

// Ruta para la pagina de tareas
Route::get('/tasks', function(){
    return view('tasks-page');
})->name('tasks-page');