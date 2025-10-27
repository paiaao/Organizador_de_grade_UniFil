<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class turma extends Model{
    use HasFactory;

    protected $table = 'turmas';

    protected $fillable = [
        'turmaRepresentante',
        'qtdAlunos',
    ];

    public function aulas(){
        return $this->hasMany(turma::class, 'turmaRepresentante', 'turmaRepresentante');
    }
}
