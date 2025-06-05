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
                                   wire:click.stop="toggleTaskStatus({{ $task->id }})" {{-- .stop para que no propague el click al <li> --}}
                                   {{ $task->is_completed ? 'checked' : '' }}
                                   class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mr-3">
                            <div class="flex-grow">
                                <span class="{{ $task->is_completed ? 'line-through text-gray-500' : 'text-gray-800' }} font-medium">
                                    {{ $task->title }} {{-- Titulo de la tarea --}}
                                </span>
                                @if ($task->category)
                                    <span class="ml-2 text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 bg-blue-200 text-blue-700 rounded-full">
                                        {{ $task->category->name }} {{-- Categoria de la tarea --}}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center ml-4">
                            <span class="text-sm {{ $task->is_completed ? 'text-green-600 font-semibold' : 'text-yellow-600 font-semibold' }}">
                                {{ $task->is_completed ? 'Completada' : 'Pendiente' }} {{-- Estado de la tarea --}}
                            </span>
                            {{-- Icono ojo para ver detalles --}}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400 ml-2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
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
        {{-- Opcional: Mostrar un placeholder si no hay tarea seleccionada y la columna debe existir --}}
        <div class="w-full md:col-span-1 mt-6 md:mt-0 p-4 bg-white shadow rounded-lg text-gray-400 italic hidden md:block">
            Selecciona una tarea para ver sus detalles.
        </div>
    @endif
</div>