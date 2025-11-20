<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\aula;

class AulaController extends Controller{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $aula = Aula::with(['uc', 'professor'])->get();
        return response()->json($aula, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        $validated = $request->validate([
            'professor_id' => 'required|exists:usuario,id',
            'uc_id' => 'required|exists:uc,id',
        ]);

        $aula = Aula::create($validated);

        $aula->load(['uc', 'professor']);

        return response()->json($aula, 201);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Display the specified resource.
     */
    public function show($id){
        $aula = Aula::with(['uc', 'professor'])->findOrFail($id);
        return response()->json($aula, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id){
        $aula = Aula::findOrFail($id);

        $validated = $request->validate([
            'professor_id' => 'sometimes|required|exists:usuario,id',
            'uc_id' => 'sometimes|required|exists:uc,id',
        ]);

        $aula->update($validated);

        $aula->load(['uc', 'professor']);

        return response()->json($aula, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id){
        $aula = Aula::findOrFail($id);
        $aula->delete();

        return response()->json(null, 204);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Buscar aulas por id do professor
     */
    public function buscaPorProfessor($professorId){
        $aula = Aula::with(['uc', 'professor'])
                    ->where('professor_id', $professorId)
                    ->get();
        
        return response()->json($aula, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Buscar aulas por id da UC
     */
    public function buscaPorUC($ucId){
        $aula = Aula::with(['uc', 'professor'])
                    ->where('uc_id', $ucId)
                    ->get();
        
        return response()->json($aula, 200);
    }
}