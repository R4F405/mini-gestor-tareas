<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use App\Models\Task;
use Illuminate\Support\Collection;

class TaskList extends Component
{
    public $tasks; // Array para almacenar las tareas

    public string $newTitle = ""; //Input del nuevo titulo de la tarea
    public ?string $newCategoryId = null; // Input de la nueva categoría de la tarea (Opcional y nulleable)
    public Collection $categories; //Almacenar las categorías disponibles

    public ?Task $selectedTask = null; // Para almacenar la tarea seleccionada

    // Se ejecuta cuando se inicializa
    public function mount()
    {
        $this->loadTasks();
        $this->loadCategories();
    }

    // Obtener todas las tareas
    public function loadTasks()
    {
        $this->tasks = Task::with('category')->latest()->get();
    }

    //Obtener todas las categorias
    public function loadCategories()
    {
        $this->categories = Category::orderBy('name')->get();

    }

    //Crear una nueva tarea
    public function addTask()
    {
        //Validar datos antes de crear la tarea
        $this->validate([
            'newTitle' => 'required|string|min:3|max:255', // Titulo obligatorio
            'newCategoryId' => 'nullable|integer|exists:categories,id' //Categoria opcional
        ]);

        //Creacion de la nueva tarea
        $task = new Task();
        $task->title = $this->newTitle;
        $task->description = null;
        $task->category_id = $this->newCategoryId;
        $task->is_completed = false;

        $task->save();

        //Limpiar campos
        $this->reset(['newTitle', 'newCategoryId']);

        //Recargar las tareas
        $this->loadTasks();
    }

    //Cambiar estado de la tarea
    public function toggleTaskStatus (int $taskId)
    {
        $task = Task::find($taskId);

        if ($task) {
            $task->is_completed = !$task->is_completed; //Invertir estado actual
            $task->save();
            $this->loadTasks(); //Recargar las tareas
        
        }
    }

    // Seleccionar una tarea y mostrar sus detalles
    public function selectTask(int $taskId)
    {
        $this->selectedTask = Task::with('category')->find($taskId);
    }

    // Cerrar el panel de detalles
    public function closeTaskDetails()
    {
        $this->selectedTask = null;
    }

     // Devolver la vista del componente
    public function render()
    {
        return view('livewire.task-list');
    }
}