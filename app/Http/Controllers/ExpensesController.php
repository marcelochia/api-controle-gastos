<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;

class ExpensesController extends Controller
{
    public function index()
    {
        return response()->json(Expense::all());
    }

    public function store(ExpenseRequest $request)
    {
        $date = new \DateTimeImmutable($request->data);

        $expense = $this->expenseExist($request->descricao, $date->format('Y-m'));
        
        if (!is_null($expense)) {
            return response()->json([
                'message' => "A despesa '{$request->descricao}' já foi informada para este mês"
            ]);
        }

        return response()->json(Expense::create([
            'descricao' => $request->descricao,
            'valor' => $request->valor,
            'data' => $date->format('Y-m-d')
        ]), 201);
    }

    public function show(int $id)
    {
        $expense = Expense::find($id);

        if (is_null($expense)) {
            return response()->noContent();
        }

        return response()->json($expense);
    }

    public function update(Expense $expense, ExpenseRequest $request)
    {
        $date = new \DateTimeImmutable($request->data);

        $description = Expense::where('descricao', $request->descricao)
                                ->where('data', 'like', $date->format('Y-m') . '%')
                                ->where('id', '<>', $expense->id)
                                ->first();
        
        if (!is_null($description)) {
            return response()->json([
                'message' => "A despesa '{$request->descricao}' já foi informada para o mês"
            ]);
        }

        $expense->fill([
            'descricao' => $request->descricao,
            'valor' => $request->valor,
            'data' => $date->format('Y-m-d')
        ]);
        $expense->save();

        return response()->json($expense);
    }

    public function destroy(int $id)
    {
        Expense::destroy($id);

        return response()->noContent();
    }

    private function expenseExist(string $description, string $date): ?Expense
    {
        return Expense::where('descricao', $description)
                        ->where('data', 'like', $date . '%')
                        ->first();
    }
}
