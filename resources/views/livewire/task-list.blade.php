<div>
    <div class="grid grid-cols-1 md:grid-cols-3 md:gap-x-6">

        {{-- Columna Izquierda: Formulario, Filtros y Lista de Tareas --}}
        <div class="w-full md:col-span-2">

            {{-- Formulario añadir tarea --}}
            <form wire:submit.prevent="addTask" class="mb-6">
                <div class="relative">
                    <input type="text" wire:model.defer="newTitle"
                           class="w-full pl-10 pr-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Añadir una Tarea...">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </span>
                </div>
                @error('newTitle') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </form>
            <div class="flex flex-col sm:flex-row justify-between items-center mb-4 space-y-4 sm:space-y-0">

                {{-- Filtros --}}
                <div class="flex items-center space-x-4 border-b pb-2">
                    <button wire:click="setFilter('all')" class="text-sm font-medium {{ $filter === 'all' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                        Todas
                    </button>
                    <button wire:click="setFilter('pending')" class="text-sm font-medium {{ $filter === 'pending' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                        Pendientes
                    </button>
                    <button wire:click="setFilter('completed')" class="text-sm font-medium {{ $filter === 'completed' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                        Completadas
                    </button>
                </div>

                {{-- Barra de Búsqueda --}}
                <div class="relative w-full sm:w-auto">
                    <input type="text"
                        wire:model.live.debounce.300ms="search"
                        class="w-full sm:w-64 pl-10 pr-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Buscar tarea...">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                </div>
            </div>

            {{-- Lista de Tareas --}}
            @if($tasks->count())
                <ul class="space-y-3">
                    @foreach ($tasks as $task)
                        <li wire:key="task-{{ $task->id }}" class="p-4 bg-white shadow rounded-lg flex justify-between items-center {{ $selectedTask && $selectedTask->id === $task->id ? 'ring-2 ring-blue-500' : '' }}">
                            <div class="flex items-center flex-grow">

                                {{-- Checkbox e informacion de la tarea --}}
                                <input type="checkbox"
                                       id="task-{{ $task->id }}"
                                       wire:click.stop="toggleTaskStatus({{ $task->id }})"
                                       {{ $task->is_completed ? 'checked' : '' }}
                                       class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mr-3">
                                <div class="flex-grow">
                                    <span class="{{ $task->is_completed ? 'line-through text-gray-500' : 'text-gray-800' }} font-medium">
                                        {{ $task->title }}
                                    </span>
                                    @if ($task->category)
                                        <span class="ml-2 text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 bg-red-200 text-red-700 rounded-full">
                                            {{ $task->category->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                             {{-- Iconos de acciones --}}
                            <div class="flex items-center ml-4 space-x-2">
                                <button wire:click="selectTask({{ $task->id }})" class="text-gray-400 hover:text-gray-600">
                                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>

                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" @click.away="open = false" class="text-gray-500 hover:text-gray-700">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                                    </button>
                                    <div x-show="open" x-transition class="absolute right-0 z-10 mt-2 w-48 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none py-1" style="display: none;">
                                        <button wire:click.stop="openCategoryModal({{ $task->id }})" @click="open = false" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Agregar categoría
                                        </button>
                                        <button wire:click.stop="openEditModal({{ $task->id }})" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Editar
                                        </button>
                                        <button wire:click.stop="openDeleteModal({{ $task->id }})" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="p-4 bg-white shadow rounded-lg text-gray-700">
                    No hay tareas que coincidan con el filtro. ¡Añade una nueva!
                </div>
            @endif
        </div>

        {{-- Columna Derecha: Añadir y listar categorias, exportar y detalles de la tarea  --}}
        <div class="w-full md:col-span-1 mt-6 md:mt-0 space-y-6">

            {{-- Componente para añadir y listar categorias --}}
            @livewire('category-manager')

            {{-- Exportar --}}
            <div class="p-4 bg-white shadow rounded-lg">
                <h3 class="text-lg font-semibold mb-3">Exportar</h3>
                <div class="flex space-x-2">
                    <button class="w-full text-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                        Exportar PDF
                    </button>
                    <button class="w-full text-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500">
                        Exportar CSV
                    </button>
                </div>
            </div>

            {{-- Detalles de la Tarea --}}
            @if ($selectedTask)
                <div class="p-4 bg-white shadow rounded-lg">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Detalles de la Tarea</h3>
                        <button wire:click="closeTaskDetails" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800">{{ $selectedTask->title }}</h4>
                        @if ($selectedTask->category)
                            <p class="text-sm text-gray-500 mt-1">
                                Categoría: <span class="font-semibold">{{ $selectedTask->category->name }}</span>
                            </p>
                        @endif
                        <p class="text-sm text-gray-500 mt-1">
                            Estado: <span class="font-semibold {{ $selectedTask->is_completed ? 'text-green-600' : 'text-yellow-600' }}">{{ $selectedTask->is_completed ? 'Completada' : 'Pendiente' }}</span>
                        </p>
                        <div class="mt-3 pt-3 border-t text-sm text-gray-700">
                            @if ($selectedTask->description)
                                <p class="font-semibold mb-1">Descripción:</p>
                                <p>{!! nl2br(e($selectedTask->description)) !!}</p>
                            @else
                                <p class="text-gray-400 italic">Esta tarea no tiene descripción.</p>
                            @endif
                        </div>
                        <div class="mt-4 pt-3 border-t">
                            <p class="text-xs text-gray-400">ID: {{ $selectedTask->id }}</p>
                            <p class="text-xs text-gray-400">Creada: {{ $selectedTask->created_at->format('d/m/Y H:i') }}</p>
                            <p class="text-xs text-gray-400">Actualizada: {{ $selectedTask->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="p-4 bg-white shadow rounded-lg text-gray-400 italic hidden md:block">
                    Selecciona una tarea para ver sus detalles.
                </div>
            @endif
        </div>
    </div>

    {{-- Modal de Confirmación de Eliminación --}}
    @if ($showDeleteModal)
    <div x-data="{ showModal: @entangle('showDeleteModal') }"
         x-show="showModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-700 bg-opacity-75 transition-opacity flex items-center justify-center z-50 p-4"
         style="display: none;" {{-- Alpine controlará la visualizacion --}}
         @keydown.escape.window="showModal = false; $wire.closeDeleteModal()" {{-- Cerrar con Escape --}}
         >
        <div @click.away="showModal = false; $wire.closeDeleteModal()" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <h3 class="text-xl font-semibold text-gray-900 mb-2">¿Estás seguro que deseas eliminar?</h3>
            <div class="mt-2 mb-4">
                <p class="text-sm text-gray-600">Estás a punto de eliminar la tarea:</p>
                <p class="text-sm text-gray-800 font-medium mt-1">• {{ $taskToDeleteTitle }}</p>
            </div>
            <div class="mt-5 sm:mt-6 flex flex-col sm:flex-row-reverse sm:space-x-3 sm:space-x-reverse">
                {{-- Boton para eliminar --}}
                <button wire:click="deleteTask" type="button"
                        class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                    Eliminar
                </button>
                {{-- Boton para cancelar --}}
                <button wire:click="closeDeleteModal" type="button"
                        @click="showModal = false"
                        class="mt-3 w-full sm:mt-0 sm:w-auto inline-flex justify-center rounded-md border border-blue-600 shadow-sm px-4 py-2 bg-white text-base font-medium text-blue-700 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal de Edición de Tarea --}}
    @if ($showEditModal)
    <div x-data="{ showEditModalAlpine: @entangle('showEditModal') }"
         x-show="showEditModalAlpine"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-700 bg-opacity-75 transition-opacity flex items-center justify-center z-50 p-4"
         style="display: none;" {{-- Alpine controlará la visualización --}}
         @keydown.escape.window="showEditModalAlpine = false; $wire.closeEditModal()" {{-- Cerrar con Escape --}}
         >
        <div @click.away="showEditModalAlpine = false; $wire.closeEditModal()" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Editar</h3>
            <form wire:submit.prevent="updateTask">
                <div class="space-y-4">
                    <div>
                        {{-- Label e input para el titulo--}}
                        <label for="editingTaskTitle" class="block text-sm font-medium text-gray-700">Título <span class="text-red-500">*</span></label>
                        <input type="text" wire:model.lazy="editingTaskTitle" id="editingTaskTitle"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        @error('editingTaskTitle') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        {{-- Label y Textarea para la descripcion --}}
                        <label for="editingTaskDescription" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea wire:model.lazy="editingTaskDescription" id="editingTaskDescription" rows="4"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                        @error('editingTaskDescription') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-6 flex flex-col sm:flex-row-reverse sm:space-x-3 sm:space-x-reverse">
                    {{-- Boton para guardar cambios --}}
                    <button type="submit"
                            class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                        Guardar
                    </button>
                    {{-- Boton para cancelar cambios --}}
                    <button type="button" wire:click="closeEditModal" @click="showEditModalAlpine = false"
                            class="mt-3 w-full sm:mt-0 sm:w-auto inline-flex justify-center rounded-md border border-blue-600 shadow-sm px-4 py-2 bg-white text-base font-medium text-blue-700 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

     {{-- Modal de Asignación de Categoría --}}
    @if ($showCategoryModal)
    <div x-data="{ showCategoryModalAlpine: @entangle('showCategoryModal') }"
         x-show="showCategoryModalAlpine"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-700 bg-opacity-75 transition-opacity flex items-center justify-center z-50 p-4"
         style="display: none;"
         @keydown.escape.window="showCategoryModalAlpine = false; $wire.closeCategoryModal()">
        <div @click.away="showCategoryModalAlpine = false; $wire.closeCategoryModal()" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $taskToCategorizeTitle }}</h3>
            <p class="text-sm text-gray-600 mb-4">Selecciona una categoría para esta tarea.</p>
            <form wire:submit.prevent="assignCategory">
                <div>
                    <label for="taskCategory" class="sr-only">Categoría</label>
                    <select wire:model="selectedCategoryForTask" id="taskCategory"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">Sin categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedCategoryForTask') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="mt-6 flex flex-col sm:flex-row-reverse sm:space-x-3 sm:space-x-reverse">
                    <button type="submit"
                            class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                        Guardar
                    </button>
                    <button type="button" wire:click="closeCategoryModal" @click="showCategoryModalAlpine = false"
                            class="mt-3 w-full sm:mt-0 sm:w-auto inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>