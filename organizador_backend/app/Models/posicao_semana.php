<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class posicao_semana extends Model{
    use HasFactory;

    protected $table = 'posicao_semana';

    protected $fillable = [
        'diaSemana',
        'horario',
    ];

    protected $casts = [
        'horario' => 'datetime:H:i:s',
    ];
}
