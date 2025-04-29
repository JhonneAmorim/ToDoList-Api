<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TodoList;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\TodoListRequest;
use Exception;
use Illuminate\Support\Facades\DB;

class TodoListController extends Controller
{
    public function index(): JsonResponse
    {
        $todoList = TodoList::orderBy('id', 'DESC')->paginate(15);

        if ($todoList->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nenhuma tarefa encontrada',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Tarefas listadas com sucesso',
            'data' => $todoList,
        ], 200);
    }

    public function show(TodoList $id): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Tarefa encontrada com sucesso',
            'data' => $id,
        ], 200);
    }

    public function store(TodoListRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();
            $todoList = TodoList::create($data);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Tarefa cadastrada com sucesso',
                'data' => $todoList,
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao cadastrar tarefa',
                'data' => $e->getMessage(),
            ], 500);
        }
    }
}
