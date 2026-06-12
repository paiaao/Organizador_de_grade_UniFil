<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\horarios;

class horariosController extends Controller{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $horarios = Horarios::with([
            'turma', 
            'posicaoSemana', 
            'aula', 
            'sala', 
            'professor'
        ])->get();
        
        return response()->json($horarios, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        $validated = $request->validate([
            'turma_id' => 'required|exists:turma,id',
            'posicaoSemana_id' => 'nullable|exists:posicaoSemana,id',
            'aula_id' => 'required|exists:aula,id',
            'sala_id' => 'required|exists:sala,id',
            'professor_id' => 'required|exists:usuario,id',
        ]);

        $horario = Horarios::create($validated);

        $horario->load([
            'turma', 
            'posicaoSemana', 
            'aula', 
            'sala', 
            'professor'
        ]);

        return response()->json($horario, 201);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Display the specified resource.
     */
    public function show($id){
        $horario = Horarios::with([
            'turma', 
            'posicaoSemana', 
            'aula', 
            'sala', 
            'professor'
        ])->findOrFail($id);
        
        return response()->json($horario, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id){
        $horario = Horarios::findOrFail($id);

        $validated = $request->validate([
            'turma_id' => 'sometimes|required|exists:turma,id',
            'posicaoSemana_id' => 'sometimes|nullable|exists:posicaoSemana,id',
            'aula_id' => 'sometimes|required|exists:aula,id',
            'sala_id' => 'sometimes|required|exists:sala,id',
            'professor_id' => 'sometimes|required|exists:usuario,id',
        ]);

        $horario->update($validated);

        $horario->load([
            'turma', 
            'posicaoSemana', 
            'aula', 
            'sala', 
            'professor'
        ]);

        return response()->json($horario, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id){
        $horario = Horarios::findOrFail($id);
        $horario->delete();

        return response()->json(null, 204);
    }

    //-----------------------------------------------------------------------------------

    /**
     * busca horarios por turma
     */
    public function porTurma($turmaId){
        $horarios = Horarios::with([
            'turma', 
            'posicaoSemana', 
            'aula', 
            'sala', 
            'professor'
        ])->where('turma_id', $turmaId)
        ->get();
        
        return response()->json($horarios, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * busca horarios por professor
     */
    public function porProfessor($professorId){
        $horarios = Horarios::with([
            'turma', 
            'posicaoSemana', 
            'aula', 
            'sala', 
            'professor'
        ])->where('professor_id', $professorId)
        ->get();
        
        return response()->json($horarios, 200);
    }

    //-----------------------------------------------------------------------------------


    /**
     * busca horarios por posicao da semana
     */
    public function porPosicaoSemana($posicaoSemanaId){
        $horarios = Horarios::with([
            'turma', 
            'posicaoSemana', 
            'aula', 
            'sala', 
            'professor'
        ])->where('posicaoSemana_id', $posicaoSemanaId)
        ->get();
        
        return response()->json($horarios, 200);
    }
}