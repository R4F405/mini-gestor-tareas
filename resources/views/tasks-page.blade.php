<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mini Gestor de Tareas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="container mx-auto mt-5 p-4">
        <div class="flex flex-col md:flex-row md:space-x-6">
            <div class="w-full md:w-2/3">
                @livewire('task-list')
            </div>
        </div>
    </div>
    @livewireScripts
</body>
</html>