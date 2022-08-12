<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomeRequest;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomesController extends Controller
{
    public function index(Request $request)
    {
        $incomes = Income::where('descricao', 'like', '%'.$request->query('descricao').'%')
                        ->get();

        if (count($incomes) === 0) {
            return response()->noContent();
        }

        return response()->json($incomes);
    }

    public function listByMonth(string $year, string $month)
    {
        $incomes = Income::whereYear('data', $year)
                        ->whereMonth('data', $month)
                        ->get();

        if (count($incomes) === 0) {
            return response()->noContent();
        }

        return response()->json($incomes);
    }

    public function store(IncomeRequest $request)
    {
        $date = new \DateTimeImmutable($request->data);

        $description = $this->existingIncome($request->descricao, $date);
        
        if (!is_null($description)) {
            return response()->json([
                'message' => "A receita '{$request->descricao}' já foi informada para este mês"
            ], 409);
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

        $description = $this->existingIncome($request->descricao, $date, $income->id);

        if (!is_null($description)) {
            return response()->json([
                'message' => "A receita '{$request->descricao}' já foi informada para o mês"
            ], 409);
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

    private function existingIncome(string $description, \DateTimeInterface $date, int $id = 0)
    {
        $result = DB::table('receitas')
                    ->where('descricao', $description)
                    ->where('id', '<>', $id)
                    ->whereMonth('data', '=', $date->format('m'))
                    ->whereYear('data', '=', $date->format('Y'))
                    ->value('descricao');

        return $result;
    }
}
