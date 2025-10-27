<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class sala extends Model{
    use HasFactory;

    protected $table = 'sala';

    protected $fillable = [
        'nomeSala',
        'tamanho',
        'disponibilidade',
    ];

    protected $casts = [
        'disponibilidade' => 'boolean',
    ];
    
    public function horarios(){
        return $this->hasMany(horarios::class);
    }
}