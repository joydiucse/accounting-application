<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Expense;
use App\Models\DollarIncome;
use App\Models\DollarExpense;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FinancialReportExport;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        // Calculate totals
        $totalIncome = Income::sum('amount');
        $totalExpenses = Expense::sum('amount');
        $currentBalance = $totalIncome - $totalExpenses;
        
        // Calculate dollar totals
        $totalDollarIncomeUSD = DollarIncome::sum('amount');

        $totalDollarExpensesUSD = DollarExpense::sum('amount');
        $totalDollarExpensesBDT = DollarExpense::sum('bdt_amount');
        $dollarBalanceUSD = $totalDollarIncomeUSD - $totalDollarExpensesUSD;
        $dollarBalanceBDT = 0; // BDT calculations removed for dollar income
        
        // Monthly data for current year
        $monthlyIncome = Income::selectRaw('MONTH(date) as month, SUM(amount) as total')
            ->whereYear('date', $currentYear)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();
            
        $monthlyExpenses = Expense::selectRaw('MONTH(date) as month, SUM(amount) as total')
            ->whereYear('date', $currentYear)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();
        
        // Fill missing months with 0
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData['income'][] = $monthlyIncome[$i] ?? 0;
            $chartData['expenses'][] = $monthlyExpenses[$i] ?? 0;
        }
        
        // Recent transactions
        $recentIncomes = Income::with(['user', 'category'])
            ->latest('date')
            ->take(5)
            ->get();
            
        $recentExpenses = Expense::with(['user', 'category'])
            ->latest('date')
            ->take(5)
            ->get();
            
        $recentDollarIncomes = DollarIncome::with(['user', 'category'])
            ->latest('date')
            ->take(3)
            ->get();
            
        $recentDollarExpenses = DollarExpense::with(['user', 'category'])
            ->latest('date')
            ->take(3)
            ->get();
        
        return view('dashboard', compact(
            'totalIncome',
            'totalExpenses', 
            'currentBalance',
            'totalDollarIncomeUSD',

            'totalDollarExpensesUSD',
            'totalDollarExpensesBDT',
            'dollarBalanceUSD',
            'dollarBalanceBDT',
            'chartData',
            'recentIncomes',
            'recentExpenses',
            'recentDollarIncomes',
            'recentDollarExpenses'
        ));
    }
    
    public function reports(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // Get filtered data
        $incomes = Income::with(['user', 'category'])
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->get();
            
        $expenses = Expense::with(['user', 'category'])
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->get();
            
        // Get filtered dollar data
        $dollarIncomes = DollarIncome::with(['user', 'category'])
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->get();
            
        $dollarExpenses = DollarExpense::with(['user', 'category'])
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->get();
        
        // Calculate totals
        $totalIncome = $incomes->sum('amount');
        $totalExpenses = $expenses->sum('amount');
        $netProfit = $totalIncome - $totalExpenses;
        
        // Calculate dollar totals
        $totalDollarIncomeUSD = $dollarIncomes->sum('amount');
        $totalDollarIncomeBDT = 0; // BDT calculations removed for dollar income
        $totalDollarExpensesUSD = $dollarExpenses->sum('amount');
        $totalDollarExpensesBDT = $dollarExpenses->sum('bdt_amount');
        $dollarNetProfitUSD = $totalDollarIncomeUSD - $totalDollarExpensesUSD;
        $dollarNetProfitBDT = 0 - $totalDollarExpensesBDT; // BDT calculations removed for dollar income
        
        // Group by categories
        $incomeByCategory = $incomes->groupBy('category.name')
            ->map(function ($items) {
                return [
                    'total' => $items->sum('amount'),
                    'count' => $items->count(),
                    'items' => $items
                ];
            });
            
        $expenseByCategory = $expenses->groupBy('category.name')
            ->map(function ($items) {
                return [
                    'total' => $items->sum('amount'),
                    'count' => $items->count(),
                    'items' => $items
                ];
            });
            
        // Group dollar transactions by categories
        $dollarIncomeByCategory = $dollarIncomes->groupBy('category.name')
            ->map(function ($items) {
                return [
                    'total_usd' => $items->sum('amount'),
                    'total_bdt' => 0, // BDT calculations removed for dollar income
                    'count' => $items->count(),
                    'items' => $items
                ];
            });
            
        $dollarExpenseByCategory = $dollarExpenses->groupBy('category.name')
            ->map(function ($items) {
                return [
                    'total_usd' => $items->sum('amount'),
                    'total_bdt' => $items->sum('bdt_amount'),
                    'count' => $items->count(),
                    'items' => $items
                ];
            });
        
        // Monthly breakdown for charts
        $monthlyData = [];
        $chartLabels = [];
        $incomeData = [];
        $expenseData = [];
        $period = Carbon::parse($dateFrom);
        $endPeriod = Carbon::parse($dateTo);
        
        while ($period <= $endPeriod) {
            $monthStart = $period->copy()->startOfMonth();
            $monthEnd = $period->copy()->endOfMonth();
            
            $monthlyIncome = $incomes->whereBetween('date', [$monthStart, $monthEnd])->sum('amount');
            $monthlyExpenses = $expenses->whereBetween('date', [$monthStart, $monthEnd])->sum('amount');
            
            $monthlyDollarIncomeUSD = $dollarIncomes->whereBetween('date', [$monthStart, $monthEnd])->sum('amount');
            $monthlyDollarIncomeBDT = 0; // BDT calculations removed for dollar income
            $monthlyDollarExpensesUSD = $dollarExpenses->whereBetween('date', [$monthStart, $monthEnd])->sum('amount');
            $monthlyDollarExpensesBDT = $dollarExpenses->whereBetween('date', [$monthStart, $monthEnd])->sum('bdt_amount');
            
            $monthlyData[] = [
                'month' => $period->format('M Y'),
                'income' => $monthlyIncome,
                'expenses' => $monthlyExpenses,
                'profit' => $monthlyIncome - $monthlyExpenses,
                'dollar_income_usd' => $monthlyDollarIncomeUSD,
                'dollar_income_bdt' => $monthlyDollarIncomeBDT,
                'dollar_expenses_usd' => $monthlyDollarExpensesUSD,
                'dollar_expenses_bdt' => $monthlyDollarExpensesBDT,
                'dollar_profit_usd' => $monthlyDollarIncomeUSD - $monthlyDollarExpensesUSD,
                'dollar_profit_bdt' => $monthlyDollarIncomeBDT - $monthlyDollarExpensesBDT
            ];
            
            // Prepare chart data
            $chartLabels[] = $period->format('M Y');
            $incomeData[] = $monthlyIncome;
            $expenseData[] = $monthlyExpenses;
            
            $period->addMonth();
        }
        
        // Prepare category chart data
        $categoryLabels = $incomeByCategory->keys()->toArray();
        $categoryData = $incomeByCategory->pluck('total')->toArray();
        
        return view('reports.index', compact(
            'incomes',
            'expenses',
            'dollarIncomes',
            'dollarExpenses',
            'totalIncome',
            'totalExpenses',
            'netProfit',
            'totalDollarIncomeUSD',

            'totalDollarExpensesUSD',
            'totalDollarExpensesBDT',
            'dollarNetProfitUSD',
            'dollarNetProfitBDT',
            'incomeByCategory',
            'expenseByCategory',
            'dollarIncomeByCategory',
            'dollarExpenseByCategory',
            'monthlyData',
            'dateFrom',
            'dateTo',
            'chartLabels',
            'incomeData',
            'expenseData',
            'categoryLabels',
            'categoryData'
        ));
    }
    
    public function exportPdf(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // Get the same data as reports method
        $incomes = Income::with(['user', 'category'])
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->get();
            
        $expenses = Expense::with(['user', 'category'])
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->get();
        
        $totalIncome = $incomes->sum('amount');
        $totalExpenses = $expenses->sum('amount');
        $netProfit = $totalIncome - $totalExpenses;
        
        $incomeByCategory = $incomes->groupBy('category.name')
            ->map(function ($items) {
                return [
                    'total' => $items->sum('amount'),
                    'count' => $items->count()
                ];
            });
            
        $expenseByCategory = $expenses->groupBy('category.name')
            ->map(function ($items) {
                return [
                    'total' => $items->sum('amount'),
                    'count' => $items->count()
                ];
            });
        
        $pdf = Pdf::loadView('reports.pdf', compact(
            'incomes',
            'expenses',
            'totalIncome',
            'totalExpenses',
            'netProfit',
            'incomeByCategory',
            'expenseByCategory',
            'dateFrom',
            'dateTo'
        ));
        
        return $pdf->download('financial-report-' . $dateFrom . '-to-' . $dateTo . '.pdf');
    }
    
    public function exportExcel(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        return Excel::download(
            new FinancialReportExport($dateFrom, $dateTo),
            'financial-report-' . $dateFrom . '-to-' . $dateTo . '.xlsx'
        );
    }
}