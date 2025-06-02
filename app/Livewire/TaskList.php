<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;

class TaskList extends Component
{
    public $tasks; // Array para almacenar las tareas

    // Se ejecuta cuando el componente se inicializa
    public function mount()
    {
        $this->loadTasks();
    }

    // Obtener todas las tareas
    public function loadTasks()
    {
        $this->tasks = Task::with('category')->latest()->get();
    }

     // Devolver la vista del componente
    public function render()
    {
        return view('livewire.task-list');
    }
}