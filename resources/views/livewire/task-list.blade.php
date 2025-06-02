<div>
    {{-- Formulario para añadir nueva tarea --}}
    <form wire:submit.prevent="addTask" class="mb-6 p-4 bg-gray-100 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-2">Añadir Nueva Tarea</h3>
        <div class="flex flex-wrap -mx-2">
            <div class="w-full md:w-1/2 px-2 mb-4 md:mb-0">
                {{-- label e input para añadir titulo--}}
                <label for="newTitle" class="block text-sm font-medium text-gray-700">Título <span class="text-red-500">*</span></label>
                <input type="text" wire:model.defer="newTitle" id="newTitle"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                       placeholder="Ej: Comprar leche">
                @error('newTitle') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="w-full md:w-1/2 px-2">
                {{-- label y select para añadir categoria --}}
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
            {{-- Boton para añadir nueva tarea --}}
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
            <li class="p-4 bg-white shadow rounded-lg flex justify-between items-center">
                <div class="flex items-center"> 
                    <input type="checkbox" {{-- CheckBox para marcar la tarea como completada o pendiente--}}
                           id="task-{{ $task->id }}"
                           wire:click="toggleTaskStatus({{ $task->id }})"
                           {{ $task->is_completed ? 'checked' : '' }}
                           class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mr-3">
                    <div>
                        <span class="{{ $task->is_completed ? 'line-through text-gray-500' : 'text-gray-800' }} font-medium">
                            {{ $task->title }} {{-- Mostrar titulo --}}
                        </span>
                        @if ($task->category) {{-- Mostrar categoria si existe--}}
                            <span class="ml-2 text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 bg-blue-200 text-blue-700 rounded-full">
                                {{ $task->category->name }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="text-sm {{ $task->is_completed ? 'text-green-600 font-semibold' : 'text-yellow-600 font-semibold' }}">
                        {{ $task->is_completed ? 'Completada' : 'Pendiente' }}
                    </span>
                </div>
            </li>
        @endforeach
    </ul>
@else
    <p class="text-gray-700">No hay tareas registradas todavía. ¡Añade una nueva!</p>
@endif
</div>