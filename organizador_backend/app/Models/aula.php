<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class aula extends Model{
    use HasFactory;

    protected $table = 'aula';

    protected $fillable = [
        'professor_id', 
        'uc_id',          
    ];

    public function uc(){
        return $this->belongsTo(uc::class, 'uc_id', 'id');
    }
    public function professor(){
        return $this->belongsTo(usuario::class, 'professor_id', 'id');
    }
}