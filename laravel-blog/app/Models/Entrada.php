<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo', 'texto', 'fecha', 'visible'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'fecha' => 'datetime',
    ];

    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }
}
