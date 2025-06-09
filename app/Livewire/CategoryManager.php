<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryManager extends Component
{
    public Collection $categories;
    public string $newCategoryName = '';

    public function mount()
    {
        $this->loadCategories();
    }

    //Carga todas las categorias
    public function loadCategories()
    {
        $this->categories = Category::orderBy('name')->get();
    }

    //Crea una nueva categoria
    public function addCategory()
    {
        $this->validate([
            'newCategoryName' => 'required|string|min:3|max:255|unique:categories,name',
        ]);

        $category = new Category();
        $category->name = $this->newCategoryName;
        $category->save();

        $this->reset('newCategoryName');
        $this->loadCategories();

        // LLama al evento para refrescar las categorias
        $this->dispatch('category-updated');
    }

    public function render()
    {
        return view('livewire.category-manager');
    }
}