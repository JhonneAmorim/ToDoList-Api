<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::orderBy('id', 'DESC')->paginate(15);

        return response()->json([
            'stauts' => 'success',
            'message' => 'Usuarios listados com sucesso',
            'data' => $users,
        ], 200);
    }

    public function show(User $id): JsonResponse
    {
        if (!$id) {
            return response()->json([
                'stauts' => 'error',
                'message' => 'Usuario não encontrado',
            ], 400);
        }

        return response()->json([
            'stauts' => 'success',
            'message' => 'Usuario encontrado com sucesso',
            'data' => $id,
        ], 200);
    }

    public function store(UserRequest $request): JsonResponse
    {
        // iniciar transação
        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password, ['rounds' => 12]),
            ]);
            // commit transação
            DB::commit();
            return response()->json([
                'stauts' => 'success',
                'message' => 'Usuario cadastrado com sucesso',
                'data' => $user,
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'stauts' => 'error',
                'message' => 'Erro ao cadastrar usuario',
            ], 400);
        }
    }

    public function update(UserRequest $request, User $id): JsonResponse
    {
        if (!$id) {
            return response()->json([
                'stauts' => 'error',
                'message' => 'Usuario não encontrado',
            ], 400);
        }
        // iniciar transação
        DB::beginTransaction();

        try {
            $id->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password, ['rounds' => 12]),
            ]);
            // commit transação
            DB::commit();
            return response()->json([
                'stauts' => 'success',
                'message' => 'Usuario atualizado com sucesso',
                'data' => $id,
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'stauts' => 'error',
                'message' => 'Erro ao atualizar usuario',
            ], 400);
        }
    }

    public function destroy(User $id): JsonResponse
    {
        if (!$id) {
            return response()->json([
                'stauts' => 'error',
                'message' => 'Usuario não encontrado',
            ], 400);
        }

        // iniciar transação
        DB::beginTransaction();

        try {
            $id->delete();
            // commit transação
            DB::commit();
            return response()->json([
                'stauts' => 'success',
                'message' => 'Usuario deletado com sucesso',
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'stauts' => 'error',
                'message' => 'Erro ao deletar usuario',
            ], 400);
        }
    }
}
