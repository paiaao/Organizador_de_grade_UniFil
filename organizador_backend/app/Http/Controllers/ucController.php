<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Uc;

class UcController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ucs = Uc::with(['aulas'])->get();
        return response()->json($ucs, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomeUC' => 'required|string|max:255',
            'tipo' => 'required|string|max:255',
            'cargahoraria' => 'required|integer|min:1',
        ]);

        $uc = Uc::create($validated);

        return response()->json($uc, 201);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $uc = Uc::with(['aulas'])->findOrFail($id);
        return response()->json($uc, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $uc = Uc::findOrFail($id);

        $validated = $request->validate([
            'nomeUC' => 'sometimes|required|string|max:255',
            'tipo' => 'sometimes|required|string|max:255',
            'cargahoraria' => 'sometimes|required|integer|min:1',
        ]);

        $uc->update($validated);

        return response()->json($uc, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $uc = Uc::findOrFail($id);
        $uc->delete();

        return response()->json(null, 204);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Buscar UCs por tipo
     */
    public function porTipo($tipo)
    {
        $ucs = Uc::where('tipo', 'like', "%{$tipo}%")
                    ->get();
        
        return response()->json($ucs, 200);
    }
}