<?php

namespace Tests\Feature;

use App\Livewire\TaskList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('can create a new task with a title only', function () {
    // Montamos el componente de Livewire
    Livewire::test(TaskList::class)
        ->set('newTitle', 'My First Task') // Establecemos el valor para el título de la nueva tarea
        ->call('addTask'); // Llamamos a la acción para añadir la tarea

    // Verificamos que la tarea fue creada en la base de datos
    $this->assertDatabaseHas('tasks', [
        'title' => 'My First Task',
        'description' => null, // Verificamos que la descripción es nula, según los requisitos
        'is_completed' => false,
    ]);
});