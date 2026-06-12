<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class horarios extends Model{
    use HasFactory;

    protected $table = 'horarios';
    protected $fillable = [
        'turma_id',
        'posicaoSemana_id', 
        'aula_id',
        'sala_id',
        'professor_id',
    ];

    public function turma(){
        return $this->belongsTo(turma::class, 'turma_id', 'id');
    }

    public function posicaoSemana(){
        return $this->belongsTo(posicaoSemana::class, 'posicaoSemana_id', 'id');
    }

    public function aula(){
        return $this->belongsTo(aula::class, 'aula_id', 'id');
    }

    public function sala(){
        return $this->belongsTo(sala::class, 'sala_id', 'id');
    }

    public function professor(){
        return $this->belongsTo(usuario::class, 'professor_id', 'id');
    }
}
