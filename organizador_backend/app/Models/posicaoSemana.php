<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class posicaoSemana extends Model{
    use HasFactory;

    protected $table = 'posicaoSemana';

    protected $fillable = [
        'diaSemana',
        'horario',
    ];

    protected $casts = [
        'horario' => 'datetime:H:i:s',
    ];
}
