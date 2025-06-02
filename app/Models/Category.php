<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Category extends Model
{

    use HasFactory; //Para datos de prueba

    //Obtiene las tareas que tiene la categoria
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
