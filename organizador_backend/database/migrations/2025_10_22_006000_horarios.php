<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    /**
     * Run the migrations.
     */
    public function up(): void{
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('turma_id')->constrained('turma')->onDelete('cascade');
            $table->foreignId('posicaoSemana_id')->constrained('posicaoSemana')->onDelete('cascade');
            $table->foreignId('aula_id')->constrained('aula')->onDelete('cascade');
            $table->foreignId('sala_id')->constrained('sala')->onDelete('cascade');
            $table->foreignId('professor_id')->constrained('usuario')->onDelete('cascade');
            $table->timestamps();
    
            $table->index(['turma_id', 'posicaoSemana_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        schema::dropIfExists('horarios');
    }
};
