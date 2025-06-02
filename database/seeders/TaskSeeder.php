<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Category;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener las categorías de trabajo y personal
        $categoriaTrabajo = Category::where('name', 'Trabajo')->first();
        $categoriaPersonal = Category::where('name', 'Personal')->first();

        // Crear las tareas
        $task1 = new Task;
        $task1->title = 'Reunión de equipo semanal';
        $task1->description = 'Preparar los puntos para la reunión del lunes.';
        if ($categoriaTrabajo) {
            $task1->category_id = $categoriaTrabajo->id;
        }
        $task1->is_completed = false;
        $task1->save();

        $task2 = new Task;
        $task2->title = 'Hacer la compra';
        $task2->description = 'Comprar leche, pan y huevos.';
        if ($categoriaPersonal) {
            $task2->category_id = $categoriaPersonal->id;
        }
        $task2->is_completed = true;
        $task2->save();

        // Sin categoria
        $task3 = new Task;
        $task3->title = 'Estudiar Laravel Livewire';
        $task3->description = 'Completar el capítulo de componentes.';
        $task3->is_completed = false;
        $task3->save();

        // Sin categoria ni descripcion
        $task4 = new Task;
        $task4->title = 'Renovar membresía del gym'; 
        $task4->is_completed = false;
        $task4->save();

        // Sin categoria ni descripcion
        $task5 = new Task;
        $task5->title = 'Enviar documento al correo de Jhon';
        $task5->is_completed = true;
        $task5->save();
    }
}
