<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomeRequest;
use App\Models\Income;

class IncomesController extends Controller
{
    public function index()
    {
        return response()->json(Income::all());
    }

    public function store(IncomeRequest $request)
    {
        $date = new \DateTimeImmutable($request->data);

        $income = $this->incomeExist($request->descricao, $date->format('Y-m'));
        
        if (!is_null($income)) {
            return response()->json([
                'message' => "A receita '{$request->descricao}' já foi informada para este mês"
            ]);
        }

        return response()->json(Income::create([
            'descricao' => $request->descricao,
            'valor' => $request->valor,
            'data' => $date->format('Y-m-d')
        ]), 201);
    }

    public function show(int $id)
    {
        $income = Income::find($id);

        if (is_null($income)) {
            return response()->noContent();
        }

        return response()->json($income);
    }

    public function update(Income $income, IncomeRequest $request)
    {
        $date = new \DateTimeImmutable($request->data);

        $description = Income::where('descricao', $request->descricao)
                                ->where('data', 'like', $date->format('Y-m') . '%')
                                ->where('id', '<>', $income->id)
                                ->first();
        
        if (!is_null($description)) {
            return response()->json([
                'message' => "A receita '{$request->descricao}' já foi informada para o mês"
            ]);
        }

        $income->fill([
            'descricao' => $request->descricao,
            'valor' => $request->valor,
            'data' => $date->format('Y-m-d')
        ]);
        $income->save();

        return response()->json($income);
    }

    public function destroy(int $id)
    {
        Income::destroy($id);

        return response()->noContent();
    }

    private function incomeExist(string $description, string $date): ?Income
    {
        return Income::where('descricao', $description)
                        ->where('data', 'like', $date . '%')
                        ->first();
    }
}
