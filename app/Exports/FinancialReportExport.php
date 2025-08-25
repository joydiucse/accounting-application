<?php

namespace App\Exports;

use App\Models\Income;
use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;

class FinancialReportExport implements WithMultipleSheets
{
    protected $dateFrom;
    protected $dateTo;
    
    public function __construct($dateFrom, $dateTo)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }
    
    public function sheets(): array
    {
        return [
            new IncomeSheet($this->dateFrom, $this->dateTo),
            new ExpenseSheet($this->dateFrom, $this->dateTo),
            new SummarySheet($this->dateFrom, $this->dateTo),
        ];
    }
}

class IncomeSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $dateFrom;
    protected $dateTo;
    
    public function __construct($dateFrom, $dateTo)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }
    
    public function collection()
    {
        return Income::with(['user', 'category'])
            ->whereBetween('date', [$this->dateFrom, $this->dateTo])
            ->orderBy('date', 'desc')
            ->get();
    }
    
    public function headings(): array
    {
        return [
            'Date',
            'Source',
            'Category',
            'Amount',
            'Description',
            'User'
        ];
    }
    
    public function map($income): array
    {
        return [
            $income->date->format('Y-m-d'),
            $income->source,
            $income->category->name ?? 'N/A',
            $income->amount,
            $income->description,
            $income->user->name
        ];
    }
    
    public function title(): string
    {
        return 'Income';
    }
}

class ExpenseSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $dateFrom;
    protected $dateTo;
    
    public function __construct($dateFrom, $dateTo)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }
    
    public function collection()
    {
        return Expense::with(['user', 'category'])
            ->whereBetween('date', [$this->dateFrom, $this->dateTo])
            ->orderBy('date', 'desc')
            ->get();
    }
    
    public function headings(): array
    {
        return [
            'Date',
            'Category',
            'Amount',
            'Description',
            'User'
        ];
    }
    
    public function map($expense): array
    {
        return [
            $expense->date->format('Y-m-d'),
            $expense->category->name ?? 'N/A',
            $expense->amount,
            $expense->description,
            $expense->user->name
        ];
    }
    
    public function title(): string
    {
        return 'Expenses';
    }
}

class SummarySheet implements FromCollection, WithHeadings, WithTitle
{
    protected $dateFrom;
    protected $dateTo;
    
    public function __construct($dateFrom, $dateTo)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }
    
    public function collection()
    {
        $totalIncome = Income::whereBetween('date', [$this->dateFrom, $this->dateTo])->sum('amount');
        $totalExpenses = Expense::whereBetween('date', [$this->dateFrom, $this->dateTo])->sum('amount');
        $netProfit = $totalIncome - $totalExpenses;
        
        return collect([
            ['Metric', 'Amount'],
            ['Total Income', $totalIncome],
            ['Total Expenses', $totalExpenses],
            ['Net Profit/Loss', $netProfit],
            ['Report Period', $this->dateFrom . ' to ' . $this->dateTo]
        ]);
    }
    
    public function headings(): array
    {
        return [];
    }
    
    public function title(): string
    {
        return 'Summary';
    }
}