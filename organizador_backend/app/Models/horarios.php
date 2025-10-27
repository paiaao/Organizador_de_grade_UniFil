<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class horarios extends Model{
    use HasFactory;

    protected $table = 'horarios';

    protected $fillable = [
        'turma_id',
        'posicao_semana_id', 
        'aula_id',
        'sala_id',
        'professor_id',
    ];

    public function turma(){
        return $this->belongsTo(Turma::class, 'turma_id', 'id');
    }

    public function posicaoSemana(){
        return $this->belongsTo(posicao_semana::class, 'posicao_semana_id', 'id');
    }

    public function aula(){
        return $this->belongsTo(Aula::class, 'aula_id', 'id');
    }

    public function sala(){
        return $this->belongsTo(Sala::class, 'sala_id', 'id');
    }

    public function professor(){
        return $this->belongsTo(Usuario::class, 'professor_id', 'id');
    }
}
