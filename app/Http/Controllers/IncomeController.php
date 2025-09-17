<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Category;
use App\Http\Requests\IncomeRequest;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Income::with(['user', 'category']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('source', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%");
            });
        }
        
        // Category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }
        
        // Calculate total income for the filtered results
        $totalIncome = $query->sum('amount');
        
        $incomes = $query->latest('date')->paginate(15);
        $categories = Category::income()->get();
        
        return view('incomes.index', compact('incomes', 'categories', 'totalIncome'));
    }

    public function create()
    {
        $categories = Category::income()->get();
        return view('incomes.create', compact('categories'));
    }

    public function store(IncomeRequest $request)
    {
        $data = [
            'date' => $request->date,
            'source' => $request->source,
            'amount' => $request->amount,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'user_id' => auth()->id(),
            'from_dollar' => $request->boolean('from_dollar'),
        ];

        // Handle dollar source incomes
        if ($request->boolean('from_dollar')) {
            $data['usd_amount'] = $request->usd_amount;
            $data['exchange_rate'] = $request->exchange_rate;
            $data['bdt_amount'] = $request->bdt_amount;
            
            // Also create a DollarExpense record (business logic: dollar income = dollar expense)
            \App\Models\DollarExpense::create([
                'date' => $request->date,
                'category' => $request->source,
                'amount' => $request->usd_amount,
                'exchange_rate' => $request->exchange_rate,
                'bdt_amount' => $request->bdt_amount,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'user_id' => auth()->id(),
            ]);
        }

        Income::create($data);

        return redirect()->route('incomes.index')
            ->with('success', 'Income created successfully.');
    }

    public function show(Income $income)
    {
        $income->load(['user', 'category']);
        return view('incomes.show', compact('income'));
    }

    public function edit(Income $income)
    {
        $categories = Category::income()->get();
        return view('incomes.edit', compact('income', 'categories'));
    }

    public function update(IncomeRequest $request, Income $income)
    {
        $data = [
            'date' => $request->date,
            'source' => $request->source,
            'amount' => $request->amount,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'from_dollar' => $request->boolean('from_dollar'),
        ];

        // Handle dollar source incomes
        if ($request->boolean('from_dollar')) {
            $data['usd_amount'] = $request->usd_amount;
            $data['exchange_rate'] = $request->exchange_rate;
            $data['bdt_amount'] = $request->bdt_amount;
            
            // Find or create corresponding DollarExpense record (business logic: dollar income = dollar expense)
            $dollarExpense = \App\Models\DollarExpense::where('user_id', auth()->id())
                ->where('date', $income->date)
                ->where('category', $income->source)
                ->where('amount', $income->usd_amount ?? 0)
                ->first();
                
            if ($dollarExpense) {
                // Update existing DollarExpense record
                $dollarExpense->update([
                    'date' => $request->date,
                    'category' => $request->source,
                    'amount' => $request->usd_amount,
                    'exchange_rate' => $request->exchange_rate,
                    'bdt_amount' => $request->bdt_amount,
                    'description' => $request->description,
                    'category_id' => $request->category_id,
                ]);
            } else {
                // Create new DollarExpense record
                \App\Models\DollarExpense::create([
                    'date' => $request->date,
                    'category' => $request->source,
                    'amount' => $request->usd_amount,
                    'exchange_rate' => $request->exchange_rate,
                    'bdt_amount' => $request->bdt_amount,
                    'description' => $request->description,
                    'category_id' => $request->category_id,
                    'user_id' => auth()->id(),
                ]);
            }
        } else {
            // Clear dollar fields if not from dollar source
            $data['usd_amount'] = null;
            $data['exchange_rate'] = null;
            $data['bdt_amount'] = null;
            
            // Remove corresponding DollarExpense record if it exists
            if ($income->from_dollar && $income->usd_amount) {
                \App\Models\DollarExpense::where('user_id', auth()->id())
                    ->where('date', $income->date)
                    ->where('category', $income->source)
                    ->where('amount', $income->usd_amount)
                    ->delete();
            }
        }

        $income->update($data);

        return redirect()->route('incomes.index')
            ->with('success', 'Income updated successfully.');
    }

    public function destroy(Income $income)
    {
        // Delete corresponding DollarExpense record if it exists (business logic: dollar income = dollar expense)
        if ($income->from_dollar && $income->usd_amount) {
            \App\Models\DollarExpense::where('user_id', $income->user_id)
                ->where('date', $income->date)
                ->where('category', $income->source)
                ->where('amount', $income->usd_amount)
                ->delete();
        }
        
        $income->delete();

        return redirect()->route('incomes.index')
            ->with('success', 'Income deleted successfully.');
    }
}
