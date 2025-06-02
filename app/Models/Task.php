<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{

    use HasFactory; //Para datos de prueba

    //Castings
    protected $casts = [
        'is_completed' => 'boolean', //Pasar de init a boolean
    ];

    //Obtiene la cateogria que tiene la tarea
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
