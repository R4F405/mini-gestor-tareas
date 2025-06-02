<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear las categorías
        
        $category1 = new Category;
        $category1->name = 'Trabajo';
        $category1->save();

        $category2 = new Category;
        $category2->name = 'Personal';
        $category2->save();

        $category3 = new Category;
        $category3->name = 'Estudios';
        $category3->save();
    }
}
