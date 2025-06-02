<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mini Gestor de Tareas</title>
    <script src="https://cdn.tailwindcss.com"></script> @livewireStyles
</head>

<body class="font-sans antialiased">
    <div class="container mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-5">Mis Tareas</h1>

    @livewire('task-list')
    </div>
    @livewireScripts
</body>

</html>