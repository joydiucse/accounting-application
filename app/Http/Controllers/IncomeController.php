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
        
        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }
        
        $incomes = $query->latest('date')->paginate(15);
        
        return view('incomes.index', compact('incomes'));
    }
    
    public function create()
    {
        $categories = Category::income()->get();
        return view('incomes.create', compact('categories'));
    }
    
    public function store(IncomeRequest $request)
    {
        Income::create([
            'date' => $request->date,
            'source' => $request->source,
            'amount' => $request->amount,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'user_id' => auth()->id(),
        ]);
        
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
        $income->update([
            'date' => $request->date,
            'source' => $request->source,
            'amount' => $request->amount,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ]);
        
        return redirect()->route('incomes.index')
            ->with('success', 'Income updated successfully.');
    }
    
    public function destroy(Income $income)
    {
        $income->delete();
        
        return redirect()->route('incomes.index')
            ->with('success', 'Income deleted successfully.');
    }
}