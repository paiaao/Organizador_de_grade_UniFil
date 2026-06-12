<?php

namespace App\Http\Controllers;

use App\Models\sala;
use Illuminate\Http\Request;

class salaController extends Controller{

    /**
    * Display a listing of the resource.
    */
    public function index(){
        return response()->json(Sala::all(), 200);
    }

//-----------------------------------------------------------------------------------

    /**
    * Store a newly created resource in storage.
    */

    public function store(Request $request){
        $validated = $request->validate([
            'nomeSala' => 'required|string|max:255',
            'tamanho' => 'required|integer|min:1',
            'disponibilidade' => 'required|boolean'
        ]);

        $sala = Sala::create($validated);
        return response()->json($sala, 201);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Display the specified resource.
     */

    public function show($id){
        $sala = Sala::findOrFail($id);
        return response()->json($sala, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id){
        $sala = Sala::findOrFail($id);

        $validated = $request->validate([
            'nomeSala' => 'sometimes|required|string|max:255',
            'tamanho' => 'sometimes|required|integer|min:1',
            'disponibilidade' => 'sometimes|required|boolean'
        ]);

        $sala->update($validated);
        return response()->json($sala, 200);
    }
    //-----------------------------------------------------------------------------------

    /**
     * Remove the specified resource from storage.
     */
    
    public function destroy($id){
        $sala = Sala::findOrFail($id);
        $sala->delete();
        return response()->json(null, 204);
    }
}