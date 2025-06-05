<div class="grid grid-cols-1 md:grid-cols-3 md:gap-x-6">
    <div class="w-full md:col-span-2">
        {{-- Formulario para añadir nueva tarea  --}}
        <form wire:submit.prevent="addTask" class="mb-6 p-4 bg-gray-50 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-2">Añadir Nueva Tarea</h3>
            <div class="flex flex-wrap -mx-2">
                <div class="w-full md:w-1/2 px-2 mb-4 md:mb-0">
                    {{-- Label e input para el titulo --}}
                    <label for="newTitle" class="block text-sm font-medium text-gray-700">Título <span class="text-red-500">*</span></label>
                    <input type="text" wire:model.defer="newTitle" id="newTitle"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                           placeholder="Ej: Comprar leche">
                    @error('newTitle') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="w-full md:w-1/2 px-2">
                    {{-- Label y select para la categoria --}}
                    <label for="newCategoryId" class="block text-sm font-medium text-gray-700">Categoría (Opcional)</label>
                    <select wire:model.defer="newCategoryId" id="newCategoryId"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Sin categoría</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('newCategoryId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="mt-4">
                {{-- Boton para añadir la tarea --}}
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition">
                    Añadir Tarea
                </button>
            </div>
        </form>

        {{-- Listado de tareas --}}
        @if($tasks->count())
            <ul class="space-y-2">
                @foreach ($tasks as $task)
                    <li wire:click="selectTask({{ $task->id }})"
                        class="p-4 bg-white shadow rounded-lg flex justify-between items-center cursor-pointer hover:bg-gray-50 {{ $selectedTask && $selectedTask->id === $task->id ? 'ring-2 ring-blue-500' : '' }}">
                        <div class="flex items-center flex-grow">
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
                                    <span class="ml-2 text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 bg-blue-200 text-blue-700 rounded-full">
                                        {{ $task->category->name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center ml-4 space-x-2">
                            <span class="text-sm {{ $task->is_completed ? 'text-green-600 font-semibold' : 'text-yellow-600 font-semibold' }}">
                                {{ $task->is_completed ? 'Completada' : 'Pendiente' }}
                            </span>
                            {{-- Icono ojo para ver detalles --}}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            {{-- Menú de tres puntos (inicio) --}}
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" @click.away="open = false" class="text-gray-500 hover:text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                    </svg>
                                </button>
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 transform scale-100"
                                     x-transition:leave-end="opacity-0 transform scale-95"
                                     class="absolute right-0 z-10 mt-2 w-48 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none py-1"
                                     style="display: none;">
                                    <button wire:click.stop="openEditModal({{ $task->id }})"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                        Editar
                                    </button>
                                    <button wire:click.stop="openDeleteModal({{ $task->id }})" 
                                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700">
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-700">No hay tareas registradas todavía. ¡Añade una nueva!</p>
        @endif
    </div>

    {{-- Detalles de la Tarea --}}
    @if ($selectedTask)
        <div class="w-full md:col-span-1 mt-6 md:mt-0 p-4 bg-white shadow rounded-lg" x-data="{ show: @json($selectedTask != null) }" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform md:translate-x-4" x-transition:enter-end="opacity-100 transform md:translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform md:translate-x-0" x-transition:leave-end="opacity-0 transform md:translate-x-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Detalles de la Tarea</h3>
                <button wire:click="closeTaskDetails" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div>
                {{-- Muestra el titulo --}}
                <h4 class="font-medium text-gray-800">{{ $selectedTask->title }}</h4>
                {{-- Muestra la categoria si existe --}}
                @if ($selectedTask->category)
                    <p class="text-sm text-gray-500 mt-1">
                        Categoría: <span class="font-semibold">{{ $selectedTask->category->name }}</span>
                    </p>
                @endif
                {{-- Muestra el estado --}}
                <p class="text-sm text-gray-500 mt-1">
                    Estado: <span class="font-semibold {{ $selectedTask->is_completed ? 'text-green-600' : 'text-yellow-600' }}">{{ $selectedTask->is_completed ? 'Completada' : 'Pendiente' }}</span>
                </p>
                {{-- Muestra la descripcion si existe --}}
                <div class="mt-3 pt-3 border-t text-sm text-gray-700">
                    @if ($selectedTask->description)
                        <p class="font-semibold mb-1">Descripción:</p>
                        <p>{!! nl2br(e($selectedTask->description)) !!}</p>
                    @else
                        <p class="text-gray-400 italic">Esta tarea no tiene descripción.</p>
                    @endif
                </div>
                {{-- Muestra id, fecha de creacion y fecha de actualizacion --}}
                <div class="mt-4 pt-3 border-t">
                    <p class="text-xs text-gray-400">ID: {{ $selectedTask->id }}</p>
                    <p class="text-xs text-gray-400">Creada: {{ $selectedTask->created_at->format('d/m/Y H:i') }}</p>
                    <p class="text-xs text-gray-400">Actualizada: {{ $selectedTask->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    @else
        {{-- Mostrar un placeholder si no hay tarea seleccionada --}}
         <div class="w-full md:col-span-1 mt-6 md:mt-0 p-4 bg-white shadow rounded-lg text-gray-400 italic hidden md:block">
            Selecciona una tarea para ver sus detalles.
        </div>
    @endif

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
</div>