<?php

namespace App\Http\Controllers;

use App\Models\DollarExpense;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DollarExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = DollarExpense::with(['user', 'category']);

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

        // Calculate total dollar expenses for the filtered results
        $totalUsdExpenses = $query->sum('amount');
        $totalBdtExpenses = $query->sum('bdt_amount');

        $dollarExpenses = $query->latest('date')->paginate(15);
        $categories = Category::expense()->get();

        return view('dollar-expenses.index', compact('dollarExpenses', 'categories', 'totalUsdExpenses', 'totalBdtExpenses'));
    }

    public function create()
    {
        $categories = Category::expense()->get();
        return view('dollar-expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'exchange_rate' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        DollarExpense::create([
            'date' => $request->date,
            'category' => $request->category,
            'amount' => $request->amount,
            'exchange_rate' => $request->exchange_rate,
            'bdt_amount' => $request->amount * $request->exchange_rate,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('dollar-expenses.index')
            ->with('success', 'Dollar expense created successfully.');
    }

    public function show(DollarExpense $dollarExpense)
    {
        $dollarExpense->load(['user', 'category']);
        return view('dollar-expenses.show', compact('dollarExpense'));
    }

    public function edit(DollarExpense $dollarExpense)
    {
        $categories = Category::expense()->get();
        return view('dollar-expenses.edit', compact('dollarExpense', 'categories'));
    }

    public function update(Request $request, DollarExpense $dollarExpense)
    {
        $request->validate([
            'date' => 'required|date',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'exchange_rate' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $dollarExpense->update([
            'date' => $request->date,
            'category' => $request->category,
            'amount' => $request->amount,
            'exchange_rate' => $request->exchange_rate,
            'bdt_amount' => $request->amount * $request->exchange_rate,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('dollar-expenses.index')
            ->with('success', 'Dollar expense updated successfully.');
    }

    public function destroy(DollarExpense $dollarExpense)
    {
        $dollarExpense->delete();

        return redirect()->route('dollar-expenses.index')
            ->with('success', 'Dollar expense deleted successfully.');
    }
}