<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use App\Models\Task;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;

class TaskList extends Component
{
    // Propiedades para el modal de creacion
    public string $newTitle = "";
    public Collection $categories;

    public ?Task $selectedTask = null;

    // Propiedades para el modal de eliminacion
    public bool $showDeleteModal = false;
    public ?int $taskToDeleteId = null;
    public string $taskToDeleteTitle = '';

    // Propiedades para el modal de edicion
    public bool $showEditModal = false;
    public ?int $editingTaskId = null;
    public string $editingTaskTitle = '';
    public ?string $editingTaskDescription = '';

    // Propiedad para el filtro
    public string $filter = 'all';

    // Se ejecuta cuando se inicializa para cargar las categorías una sola vez
    public function mount()
    {
        $this->loadCategories();
    }

    // Carga las categorias
    public function loadCategories()
    {
        $this->categories = Category::orderBy('name')->get();
    }

    //Crear una nueva tarea
    public function addTask()
    {
        $this->validate([
            'newTitle' => 'required|string|min:3|max:255',
        ]);

        $task = new Task();
        $task->title = $this->newTitle;
        $task->description = null;
        $task->is_completed = false;
        $task->save();

        $this->reset('newTitle');

    }

    //Cambiar estado de la tarea
    public function toggleTaskStatus(int $taskId)
    {
        $task = Task::find($taskId);

        if ($task) {
            $task->is_completed = !$task->is_completed;
            $task->save();
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

    // Abrir modal de confirmacion de eliminacion
    public function openDeleteModal(int $taskId)
    {
        $task = Task::find($taskId);
        if ($task) {
            $this->taskToDeleteId = $taskId;
            $this->taskToDeleteTitle = $task->title;
            $this->showDeleteModal = true;
        }
    }

    // Cerrar modal de confirmacion de eliminacion
    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->taskToDeleteId = null;
        $this->taskToDeleteTitle = '';
    }

    // Eliminar la tarea
    public function deleteTask()
    {
        if ($this->taskToDeleteId) {
            $task = Task::find($this->taskToDeleteId);
            if ($task) {
                if ($this->selectedTask && $this->selectedTask->id === $this->taskToDeleteId) {
                    $this->selectedTask = null;
                }
                $task->delete();
            }
            $this->closeDeleteModal();
        }
    }

    // Abrir modal de edicion
    public function openEditModal(int $taskId)
    {
        $task = Task::find($taskId);
        if ($task) {
            $this->editingTaskId = $task->id;
            $this->editingTaskTitle = $task->title;
            $this->editingTaskDescription = $task->description;
            $this->showEditModal = true;
        }
    }

    // Cerrar modal de edicion
    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingTaskId = null;
        $this->editingTaskTitle = '';
        $this->editingTaskDescription = '';
    }

    // Actualizar la tarea
    public function updateTask()
    {
        $validatedData = $this->validate([
            'editingTaskTitle' => 'required|string|min:3|max:255',
            'editingTaskDescription' => 'nullable|string|max:1000',
        ]);

        if ($this->editingTaskId) {
            $task = Task::find($this->editingTaskId);
            if ($task) {
                $task->title = $validatedData['editingTaskTitle'];
                $task->description = $validatedData['editingTaskDescription'];
                $task->save();

                if ($this->selectedTask && $this->selectedTask->id === $this->editingTaskId) {
                    $this->selectTask($this->editingTaskId);
                }

                $this->closeEditModal();
            }
        }
    }

    // Establecer el filtro
    public function setFilter(string $filter)
    {
        $this->filter = $filter;
    }

    // Renderiza el componente, obteniendo las tareas y pasandolas a la vista
    public function render()
    {
        $tasksQuery = Task::with('category');

        if ($this->filter === 'pending') {
            $tasksQuery->where('is_completed', false);
        } elseif ($this->filter === 'completed') {
            $tasksQuery->where('is_completed', true);
        }

        $tasks = $tasksQuery->latest()->get();

        return view('livewire.task-list', [
            'tasks' => $tasks,
            'categories' => $this->categories // Se pasan las categorias a la vista
        ]);
    }

    // Evento para refrescar las categorias
    #[On('category-updated')]
    public function refreshCategories()
    {
        $this->loadCategories();
    }
}