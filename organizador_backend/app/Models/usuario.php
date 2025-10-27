<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuario';

    protected $fillable = [
        'Permissao',
        'SenhaHash', 
        'Matricula',
        'Nome',
        'Turma_ID',
        'Curso',
        'Entrada',
    ];

    public function turma(){
        return $this->belongsTo(Turma::class, 'Turma_ID', 'id');
    }
}