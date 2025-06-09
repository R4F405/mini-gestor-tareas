<div class="p-4 bg-white shadow rounded-lg mb-6">
    {{-- Formulario para añadir nueva categoría --}}
    <form wire:submit.prevent="addCategory" class="mb-4">
        <h3 class="text-lg font-semibold mb-2">Añadir una categoría</h3>
        <div class="flex space-x-2">
            <input type="text" wire:model="newCategoryName" id="newCategoryName"
                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                   placeholder="Ej: Marketing">
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                Añadir
            </button>
        </div>
        @error('newCategoryName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </form>

    {{-- Lista de categorías --}}
    <div>
        <h3 class="text-lg font-semibold mb-2">Lista de categorías</h3>
        <div class="flex flex-wrap gap-2">
            @forelse ($categories as $category)
                <span class="inline-flex items-center font-bold leading-sm uppercase px-3 py-1 bg-blue-200 text-blue-700 rounded-full">
                    {{ $category->name }}
                </span>
            @empty
                <p class="text-gray-500 text-sm">No hay categorías.</p>
            @endforelse
        </div>
    </div>
</div>