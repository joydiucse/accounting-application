<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $incomeCategories = Category::income()->get();
        $expenseCategories = Category::expense()->get();
        
        return view('categories.index', compact('incomeCategories', 'expenseCategories'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where(function ($query) use ($request) {
                    return $query->where('type', $request->type);
                })
            ],
            'type' => 'required|in:income,expense',
        ]);
        
        Category::create([
            'name' => $request->name,
            'type' => $request->type,
            'is_default' => false,
        ]);
        
        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }
    
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where(function ($query) use ($request, $category) {
                    return $query->where('type', $request->type)
                                ->where('id', '!=', $category->id);
                })
            ],
        ]);
        
        $category->update([
            'name' => $request->name,
        ]);
        
        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }
    
    public function destroy(Category $category)
    {
        if ($category->is_default) {
            return redirect()->route('categories.index')
                ->with('error', 'Cannot delete default category.');
        }
        
        $category->delete();
        
        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}