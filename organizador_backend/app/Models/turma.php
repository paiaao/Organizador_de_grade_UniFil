<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class turma extends Model{
    use HasFactory;

    protected $table = 'turma';

    protected $fillable = [
        'nomeTurma',
        'qtdAlunos',
    ];

    public function aulas(){
        return $this->hasMany(turma::class, 'nomeTurma', 'nomeTurma');
    }
}
