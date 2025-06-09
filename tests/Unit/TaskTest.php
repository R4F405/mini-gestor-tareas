<?php

namespace Tests\Unit;

use App\Models\Task;

test('it casts is_completed to boolean', function () {
    $task = new Task();
    $task->is_completed = 1; //TRUE

    // Verificamos que el modelo convierte el valor a un booleano correctamente
    expect($task->is_completed)->toBeTrue();

    $task->is_completed = 0; //FALSE
    expect($task->is_completed)->toBeFalse();
});