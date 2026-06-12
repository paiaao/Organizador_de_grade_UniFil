<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\posicaoSemana;

class posicaoSemanaController extends Controller{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        return response()->json(posicaoSemana::all(), 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        $validated = $request->validate([
            'diaSemana' => 'required|string|max:255',
            'horario' => 'required|string',
        ]);

        $posicaoSemana = posicaoSemana::create($validated);

        return response()->json($posicaoSemana, 201);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Display the specified resource.
     */
    public function show($id){
        $posicaoSemana = posicaoSemana::findOrFail($id);
        return response()->json($posicaoSemana, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id){
        $posicaoSemana = posicaoSemana::findOrFail($id);

        $validated = $request->validate([
            'diaSemana' => 'sometimes|required|string|max:255',
            'horario' => 'sometimes|required|date_format:H:i:s',
        ]);

        $posicaoSemana->update($validated);

        return response()->json($posicaoSemana, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id){
        $posicaoSemana = posicaoSemana::findOrFail($id);
        $posicaoSemana->delete();

        return response()->json(null, 204);
    }
}