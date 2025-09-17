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
        $data = [
            'date' => $request->date,
            'category' => $request->category,
            'amount' => $request->amount,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'user_id' => auth()->id(),
            'from_dollar' => $request->boolean('from_dollar'),
        ];

        // Handle dollar source expenses
        if ($request->boolean('from_dollar')) {
            $usdAmount = $request->usd_amount;
            $availableBalance = \App\Models\Income::getAvailableDollarBalance(auth()->id());
            
            if ($usdAmount > $availableBalance) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['usd_amount' => 'Insufficient dollar balance. Available: $' . number_format($availableBalance, 2)]);
            }
            
            $data['usd_amount'] = $usdAmount;
            $data['exchange_rate'] = $request->exchange_rate;
            $data['bdt_amount'] = $request->bdt_amount;
            
            // Also create a DollarExpense record
            \App\Models\DollarExpense::create([
                'date' => $request->date,
                'category' => $request->category,
                'amount' => $usdAmount,
                'exchange_rate' => $request->exchange_rate,
                'bdt_amount' => $request->bdt_amount,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'user_id' => auth()->id(),
            ]);
        }

        Expense::create($data);

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
        $data = [
            'date' => $request->date,
            'category' => $request->category,
            'amount' => $request->amount,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'from_dollar' => $request->boolean('from_dollar'),
        ];

        // Handle dollar source expenses
        if ($request->boolean('from_dollar')) {
            $usdAmount = $request->usd_amount;
            $availableBalance = \App\Models\Income::getAvailableDollarBalance(auth()->id());
            
            // Add back the current expense's USD amount if it was from dollar source
            if ($expense->from_dollar) {
                $availableBalance += $expense->usd_amount;
            }
            
            if ($usdAmount > $availableBalance) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['usd_amount' => 'Insufficient dollar balance. Available: $' . number_format($availableBalance, 2)]);
            }
            
            $data['usd_amount'] = $usdAmount;
            $data['exchange_rate'] = $request->exchange_rate;
            $data['bdt_amount'] = $request->bdt_amount;
            
            // Find or create corresponding DollarExpense record
            $dollarExpense = \App\Models\DollarExpense::where('user_id', auth()->id())
                ->where('date', $expense->date)
                ->where('category', $expense->category)
                ->where('amount', $expense->usd_amount ?? 0)
                ->first();
                
            if ($dollarExpense) {
                // Update existing DollarExpense record
                $dollarExpense->update([
                    'date' => $request->date,
                    'category' => $request->category,
                    'amount' => $usdAmount,
                    'exchange_rate' => $request->exchange_rate,
                    'bdt_amount' => $request->bdt_amount,
                    'description' => $request->description,
                    'category_id' => $request->category_id,
                ]);
            } else {
                // Create new DollarExpense record
                \App\Models\DollarExpense::create([
                    'date' => $request->date,
                    'category' => $request->category,
                    'amount' => $usdAmount,
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
            if ($expense->from_dollar && $expense->usd_amount) {
                \App\Models\DollarExpense::where('user_id', auth()->id())
                    ->where('date', $expense->date)
                    ->where('category', $expense->category)
                    ->where('amount', $expense->usd_amount)
                    ->delete();
            }
        }

        $expense->update($data);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        // Delete corresponding DollarExpense record if it exists
        if ($expense->from_dollar && $expense->usd_amount) {
            \App\Models\DollarExpense::where('user_id', $expense->user_id)
                ->where('date', $expense->date)
                ->where('category', $expense->category)
                ->where('amount', $expense->usd_amount)
                ->delete();
        }
        
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}
