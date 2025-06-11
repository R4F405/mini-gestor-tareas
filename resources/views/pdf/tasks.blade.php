<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Exportación de Tareas</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #dddddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .completed { text-decoration: line-through; color: #888; }
        .category { font-size: 0.8em; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Lista de Tareas</h1>
    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Categoría</th>
                <th>Estado</th>
                <th>Fecha de Creación</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tasks as $task)
                <tr>
                    <td class="{{ $task->is_completed ? 'completed' : '' }}">{{ $task->title }}</td>
                    <td>
                        @if ($task->category)
                            <span class="category">{{ $task->category->name }}</span>
                        @else
                            <span>N/A</span>
                        @endif
                    </td>
                    <td>{{ $task->is_completed ? 'Completada' : 'Pendiente' }}</td>
                    <td>{{ $task->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No hay tareas para exportar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>