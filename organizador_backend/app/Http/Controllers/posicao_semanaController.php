<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\posicao_semana;

class PosicaoSemanaController extends Controller{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        return response()->json(posicao_semana::all(), 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        $validated = $request->validate([
            'diaSemana' => 'required|string|max:255',
            'horario' => 'required|date_format:H:i:s',
        ]);

        $posicao_semana = posicao_semana::create($validated);

        return response()->json($posicao_semana, 201);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Display the specified resource.
     */
    public function show($id){
        $posicao_semana = posicao_semana::findOrFail($id);
        return response()->json($posicao_semana, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id){
        $posicao_semana = posicao_semana::findOrFail($id);

        $validated = $request->validate([
            'diaSemana' => 'sometimes|required|string|max:255',
            'horario' => 'sometimes|required|date_format:H:i:s',
        ]);

        $posicao_semana->update($validated);

        return response()->json($posicao_semana, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id){
        $posicao_semana = posicao_semana::findOrFail($id);
        $posicao_semana->delete();

        return response()->json(null, 204);
    }
}