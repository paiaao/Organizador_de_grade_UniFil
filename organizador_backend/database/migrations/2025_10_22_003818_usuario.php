<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->id(); 
            $table->integer('permissao');
            $table->string('senhaHash'); 
            $table->string('matricula')->nullable();
            $table->string('nome');
            $table->foreignId('Turma_ID')->nullable()->constrained('turma');
            $table->string('Curso')->nullable();
            $table->date('Entrada');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
