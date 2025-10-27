<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class aula extends Model{
    use HasFactory;

    protected $table = 'aula';

    protected $fillable = [
        'professor_id', 
        'UC_ID',          
    ];

    public function uc(){
        return $this->belongsTo(UC::class, 'UC_ID', 'id');
    }
}