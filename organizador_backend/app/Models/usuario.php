<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class usuario extends Model{
    protected $table = 'usuario';

    protected $fillable = [
        'permissao',
        'senhaHash', 
        'matricula',
        'nome',
        'turma_id',
        'curso',
        'entrada',
    ];

    public function turma(){
        return $this->belongsTo(turma::class, 'turma_id', 'id');
    }
}