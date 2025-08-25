<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use App\Http\Requests\ExpenseRequest;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with(['user', 'category']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('category', 'like', "%{$search}%")
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
        
        // Calculate total expenses for the filtered results
        $totalExpenses = $query->sum('amount');
        
        $expenses = $query->latest('date')->paginate(15);
        $categories = Category::expense()->get();
        
        return view('expenses.index', compact('expenses', 'categories', 'totalExpenses'));
    }
    
    public function create()
    {
        $categories = Category::expense()->get();
        return view('expenses.create', compact('categories'));
    }
    
    public function store(ExpenseRequest $request)
    {
        Expense::create([
            'date' => $request->date,
            'category' => $request->category,
            'amount' => $request->amount,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'user_id' => auth()->id(),
        ]);
        
        return redirect()->route('expenses.index')
            ->with('success', 'Expense created successfully.');
    }
    
    public function show(Expense $expense)
    {
        $expense->load(['user', 'category']);
        return view('expenses.show', compact('expense'));
    }
    
    public function edit(Expense $expense)
    {
        $categories = Category::expense()->get();
        return view('expenses.edit', compact('expense', 'categories'));
    }
    
    public function update(ExpenseRequest $request, Expense $expense)
    {
        $expense->update([
            'date' => $request->date,
            'category' => $request->category,
            'amount' => $request->amount,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ]);
        
        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }
    
    public function destroy(Expense $expense)
    {
        $expense->delete();
        
        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}