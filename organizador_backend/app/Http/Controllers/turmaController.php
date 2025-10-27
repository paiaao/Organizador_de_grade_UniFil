<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turma;

class TurmaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $turmas = Turma::with(['aulas'])->get();
        return response()->json($turmas, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'turmaRepresentante' => 'required|string|max:255',
            'qtdAlunos' => 'required|integer|min:1',
        ]);

        $turma = Turma::create($validated);

        return response()->json($turma, 201);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $turma = Turma::with(['aulas'])->findOrFail($id);
        return response()->json($turma, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $turma = Turma::findOrFail($id);

        $validated = $request->validate([
            'turmaRepresentante' => 'sometimes|required|string|max:255',
            'qtdAlunos' => 'sometimes|required|integer|min:1',
        ]);

        $turma->update($validated);

        return response()->json($turma, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $turma = Turma::findOrFail($id);
        $turma->delete();

        return response()->json(null, 204);
    }

    //-----------------------------------------------------------------------------------

    /**
     * buscar turma por representante
     */
    public function porRepresentante($representante)
    {
        $turmas = Turma::where('turmaRepresentante', 'like', "%{$representante}%")
                    ->get();
        
        return response()->json($turmas, 200);
    }
}