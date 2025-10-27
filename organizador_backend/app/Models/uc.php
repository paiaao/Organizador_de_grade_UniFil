<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class uc extends Model{
    use HasFactory;

    protected $table = 'uc';
    protected $primaryKey = 'id'; //depois eu tiro

    protected $fillable = [
        'nomeUC',
        'tipo',
        'cargahoraria',
    ];

    public function aulas(){
        return $this->hasMany(Aula::class, 'uc_id', 'id');
    }
}
