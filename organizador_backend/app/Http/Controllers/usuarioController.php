<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usuario;
use Illuminate\Support\Facades\Hash;

/** 
*!       Niveis de permissao:
*!       1 - Admin
*!       2 - Professor
*!       3 - Aluno
*/

class usuarioController extends Controller{
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
            'permissao' => 'required|integer|min:0|max:3',
            'senhaHash' => 'required|string|min:6|max:255',
            'matricula' => 'nullable|string|max:255|unique:usuario',
            'nome' => 'required|string|max:255',
            'turma_id' => 'nullable|exists:turma,id',
            'curso' => 'nullable|string|max:255',
            'entrada' => 'required|string',
        ]);

        // Aplica hash na senha antes de salvar
        $validated['senhaHash'] = Hash::make($validated['senhaHash']);

        $usuario = Usuario::create($validated);

        $usuario->load(['turma']);

        return response()->json([
            'message' => 'Usuário criado com sucesso',
            'usuario' => $usuario
        ], 201);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Display the specified resource.
     */
    public function show($id){
        $usuario = Usuario::with(['turma'])->findOrFail($id);
        return response()->json($usuario, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id){
        $usuario = Usuario::findOrFail($id);

        $validated = $request->validate([
            'permissao' => 'sometimes|required|integer|min:0|max:3',
            'senhaHash' => 'sometimes|required|string|min:6|max:255',
            'matricula' => 'sometimes|nullable|string|max:255|unique:usuario,matricula,' . $usuario->id,
            'nome' => 'sometimes|required|string|max:255',
            'turma_id' => 'sometimes|nullable|exists:turma,id',
            'curso' => 'sometimes|nullable|string|max:255',
            'entrada' => 'sometimes|required|string',
        ]);

        // Se uma nova senha foi fornecida, aplica hash
        if (isset($validated['senhaHash'])) {
            $validated['senhaHash'] = Hash::make($validated['senhaHash']);
        }

        $usuario->update($validated);

        $usuario->load(['turma']);

        return response()->json($usuario, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id){
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return response()->json(null, 204);
    }

    //-----------------------------------------------------------------------------------

    /**
     * busca usuario por nome
     */
    public function porNome($nome){
        $usuarios = Usuario::with(['turma'])
                    ->where('nome', 'like', "%{$nome}%")
                    ->get();
        
        return response()->json($usuarios, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * busca usuarios por turma
     */
    public function porTurma($turmaId){
        $usuarios = Usuario::with(['turma'])
                    ->where('turma_id', $turmaId)
                    ->get();
        
        return response()->json($usuarios, 200);
    }

    //-----------------------------------------------------------------------------------
    /**
     * busca admins por permissao
     */
    public function admins(){
    
        $admins = Usuario::where('permissao', 1)
                    ->get();
        
        return response()->json($admins, 200);
    }

    //-----------------------------------------------------------------------------------
    /**
     * busca professores por permissao
     */
    public function professores(){
    
        $professores = Usuario::where('permissao', 2)
                    ->get();
        
        return response()->json($professores, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * busca alunos por permissao
     */
    public function alunos(){
        $alunos = Usuario::where('permissao', 3)
                    ->get();
        
        return response()->json($alunos, 200);
    }

    //-----------------------------------------------------------------------------------

    /**
     * Método para login de usuário
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string', // Campo único para matricula ou nome
            'senhaHash' => 'required|string',
        ]);

        // Primeiro tenta buscar por matricula (para alunos)
        $usuario = Usuario::where('matricula', $request->login)->first();

        // Se não encontrou por matricula, busca por nome (para admins/professores)
        if (!$usuario) {
            $usuario = Usuario::where('nome', $request->login)->first();
        }

        if (!$usuario || !Hash::check($request->senhaHash, $usuario->senhaHash)) {
            return response()->json([
                'message' => 'Credenciais inválidas'
            ], 401);
        }

        return response()->json([
            'message' => 'Login realizado com sucesso',
            'usuario' => $usuario
        ], 200);
    }
}