<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SummaryController extends Controller
{
    public function summary(string $year, string $month)
    {
        $totalIncomes = Income::whereYear('data', '=', $year)
                                ->whereMonth('data', '=', $month)
                                ->sum('valor');

        $totalExpenses = Expense::whereYear('data', '=', $year)
                                ->whereMonth('data', '=', $month)
                                ->sum('valor');

        $totalExpensesPerCategory = DB::table('despesas')
                                        ->join('categorias', 'despesas.categoria_id', '=', 'categorias.id')
                                        ->select('categorias.nome as categoria', DB::raw('sum(valor) valor'))
                                        ->whereYear('data', '=', $year)
                                        ->whereMonth('data', '=', $month)
                                        ->groupBy('categoria_id')
                                        ->get();

        return response()->json([
            'competencia' => $month . '-' . $year,
            'receitas_total' => $totalIncomes,
            'despesas_total' => $totalExpenses,
            'saldo' => $totalIncomes - $totalExpenses,
            'gastos_por_categoria' => $totalExpensesPerCategory
        ]);
    }
}
