<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

/** 
*!       Niveis de permissao:
*!       0 - Admin
*!       1 - Professor
*!       2 - Aluno
*/

class UsuarioController extends Controller{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $usuarios = Usuario::with(['turma'])->get();
        return response()->json($usuarios, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        $validated = $request->validate([
            'permissao' => 'required|integer|min:0|max:2',
            'senhaHash' => 'required|string|max:255',
            'matricula' => 'nullable|string|max:255|unique:usuario',
            'nome' => 'required|string|max:255',
            'Turma_ID' => 'nullable|exists:turmas,id',
            'Curso' => 'nullable|string|max:255',
            'Entrada' => 'required|date',
        ]);

        $usuario = Usuario::create($validated);

        $usuario->load(['turma']);

        return response()->json($usuario, 201);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $usuario = Usuario::with(['turma'])->findOrFail($id);
        return response()->json($usuario, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $validated = $request->validate([
            'permissao' => 'sometimes|required|integer|min:0|max:2',
            'senhaHash' => 'sometimes|required|string|max:255',
            'matricula' => 'sometimes|nullable|string|max:255|unique:usuario,matricula,' . $usuario->id,
            'nome' => 'sometimes|required|string|max:255',
            'Turma_ID' => 'sometimes|nullable|exists:turmas,id',
            'Curso' => 'sometimes|nullable|string|max:255',
            'Entrada' => 'sometimes|required|date',
        ]);

        $usuario->update($validated);

        $usuario->load(['turma']);

        return response()->json($usuario, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return response()->json(null, 204);
    }

    //-----------------------------------------------------------------------------------

    /**
     * busca usuario por nome
     */
    public function porNome($nome)
    {
        $usuarios = Usuario::with(['turma'])
                    ->where('nome', 'like', "%{$nome}%")
                    ->get();
        
        return response()->json($usuarios, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * busca usuarios por turma
     */
    public function porTurma($turmaId)
    {
        $usuarios = Usuario::with(['turma'])
                    ->where('Turma_ID', $turmaId)
                    ->get();
        
        return response()->json($usuarios, 200);
    }

    //-----------------------------------------------------------------------------------
    /**
     * busca admins por permissao
     */
    public function admins(){
    
        $admins = Usuario::whereIn('permissao', 0)
                    ->get();
        
        return response()->json($admins, 200);
    }

    //-----------------------------------------------------------------------------------
    /**
     * busca professores por permissao
     */
    public function professores(){
    
        $professores = Usuario::whereIn('permissao', 1)
                    ->get();
        
        return response()->json($professores, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * busca alunos por permissao
     */
    public function alunos(){
        $alunos = Usuario::where('permissao', 2)
                    ->get();
        
        return response()->json($alunos, 200);
    }
}