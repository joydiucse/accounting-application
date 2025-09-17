<?php

namespace App\Http\Controllers;

use App\Models\DollarIncome;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DollarIncomeController extends Controller
{
    public function index(Request $request)
    {
        $query = DollarIncome::with(['user', 'category']);
        
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
        
        // Calculate total dollar income for the filtered results
        $totalDollarIncome = $query->sum('amount');
        
        $dollarIncomes = $query->latest('date')->paginate(15);
        $categories = Category::income()->get();
        
        return view('dollar-incomes.index', compact('dollarIncomes', 'categories', 'totalDollarIncome'));
    }

    public function create()
    {
        $categories = Category::income()->get();
        return view('dollar-incomes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'source' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        DollarIncome::create([
            'date' => $request->date,
            'source' => $request->source,
            'amount' => $request->amount,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('dollar-incomes.index')
            ->with('success', 'Dollar income created successfully.');
    }

    public function show(DollarIncome $dollarIncome)
    {
        $dollarIncome->load(['user', 'category']);
        return view('dollar-incomes.show', compact('dollarIncome'));
    }

    public function edit(DollarIncome $dollarIncome)
    {
        $categories = Category::income()->get();
        return view('dollar-incomes.edit', compact('dollarIncome', 'categories'));
    }

    public function update(Request $request, DollarIncome $dollarIncome)
    {
        $request->validate([
            'date' => 'required|date',
            'source' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $dollarIncome->update([
            'date' => $request->date,
            'source' => $request->source,
            'amount' => $request->amount,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('dollar-incomes.index')
            ->with('success', 'Dollar income updated successfully.');
    }

    public function destroy(DollarIncome $dollarIncome)
    {
        $dollarIncome->delete();

        return redirect()->route('dollar-incomes.index')
            ->with('success', 'Dollar income deleted successfully.');
    }
}