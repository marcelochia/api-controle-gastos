<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpensesController extends Controller
{
    public function index(Request $request)
    {
        $expenseModel = Expense::where('descricao', 'like', '%'.$request->query('descricao').'%')
                            ->get();

        if (count($expenseModel) === 0) {
            return response()->noContent();
        }

        $expenses = ExpenseResource::collection($expenseModel);

        return response()->json($expenses);
    }

    public function listByMonth(string $year, string $month)
    {
        $expenses = Expense::whereYear('data', $year)
                            ->whereMonth('data', $month)
                            ->get();

        if (count($expenses) === 0) {
            return response()->noContent();
        }

        return response()->json($expenses);
    }

    public function store(ExpenseRequest $request)
    {
        $date = new \DateTimeImmutable($request->data);

        $description = $this->existingExpense($request->descricao, $date);
        
        if (!is_null($description)) {
            return response()->json([
                'message' => "A despesa '{$request->descricao}' já foi informada para este mês"
            ], 409);
        }

        $expense = Expense::create([
            'descricao' => $request->descricao,
            'valor' => $request->valor,
            'data' => $date->format('Y-m-d'),
            'categoria_id' => $request->categoria_id ?? 8
        ]);

        return response()->json($expense, 201);
    }

    public function show(int $id)
    {
        $expenseModel = Expense::find($id);

        if (is_null($expenseModel)) {
            return response()->noContent();
        }

        $expense = new ExpenseResource($expenseModel);

        return response()->json($expense);
    }

    public function update(Expense $expense, ExpenseRequest $request)
    {
        $date = new \DateTimeImmutable($request->data);

        $description = $this->existingExpense($request->descricao, $date, $expense->id);

        if (!is_null($description)) {
            return response()->json([
                'message' => "A despesa '{$request->descricao}' já foi informada para o mês"
            ], 409);
        }

        $expense->fill([
            'descricao' => $request->descricao,
            'valor' => $request->valor,
            'data' => $date->format('Y-m-d'),
            'categoria_id' => $request->categoria_id ?? 8
        ]);
        $expense->save();

        return response()->json($expense);
    }

    public function destroy(int $id)
    {
        Expense::destroy($id);

        return response()->noContent();
    }

    private function existingExpense(string $description, \DateTimeInterface $date, int $id = 0)
    {
        $result = DB::table('despesas')
                    ->where('descricao', $description)
                    ->where('id', '<>', $id)
                    ->whereMonth('data', '=', $date->format('m'))
                    ->whereYear('data', '=', $date->format('Y'))
                    ->value('descricao');

        return $result;
    }
}
