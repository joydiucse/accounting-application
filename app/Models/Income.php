<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'source',
        'amount',
        'from_dollar',
        'description',
        'user_id',
        'category_id',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the user that owns the income.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that owns the income.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope a query to only include incomes from a specific date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope a query to only include incomes from current month.
     */
    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('date', Carbon::now()->month)
                    ->whereYear('date', Carbon::now()->year);
    }

    /**
     * Scope a query to only include incomes from current year.
     */
    public function scopeCurrentYear($query)
    {
        return $query->whereYear('date', Carbon::now()->year);
    }
}