<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aluno;

class AlunoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        return response()->json(Aluno::all(), 200);
    }

//-----------------------------------------------------------------------------------
    
    /**
     * Store a newly created resource in storage.
     */
    // Criar novo aluno
    public function store(Request $request){
        $validated = $request->validate([
            'nome'      => 'required|string|max:255',
            'matricula' => 'required|string|max:255|unique:alunos',
            'curso'     => 'nullable|string|max:255',
            'turma'     => 'nullable|string|max:255',
        ]);

        $aluno = Aluno::create($validated);

        return response()->json($aluno, 201);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Display the specified resource.
     */
    public function show($id){
        $aluno = Aluno::findOrFail($id);
        return response()->json($aluno, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id){
        $aluno = Aluno::findOrFail($id);

        $validated = $request->validate([
            'nome'      => 'sometimes|required|string|max:255',
            'matricula' => 'sometimes|required|string|max:255|unique:alunos,matricula,' . $aluno->id,
            'curso'     => 'nullable|string|max:255',
            'turma'     => 'nullable|string|max:255',
        ]);

        $aluno->update($validated);

        return response()->json($aluno, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Remove the specified resource from storage.
     */
public function destroy($id)
    {
        $aluno = Aluno::findOrFail($id);
        $aluno->delete();

        return response()->json(null, 204);
    }
    
}

