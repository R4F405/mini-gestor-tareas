<div>
    @if($tasks->count())
        <ul class="space-y-2">
            @foreach ($tasks as $task)
                <li class="p-4 bg-white shadow rounded-lg flex justify-between items-center">
                    <div>
                        <span class="{{ $task->is_completed ? 'line-through text-gray-500' : '' }}">
                            {{ $task->title }}
                        </span>
                        @if ($task->category)
                            <span class="ml-2 text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 bg-blue-200 text-blue-700 rounded-full">
                                {{ $task->category->name }}
                            </span>
                        @endif
                    </div>
                    <div>
                        <span class="text-sm {{ $task->is_completed ? 'text-green-500' : 'text-yellow-500' }}">
                            {{ $task->is_completed ? 'Completada' : 'Pendiente' }}
                        </span>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-gray-700">No hay tareas registradas todavía.</p>
    @endif
</div>