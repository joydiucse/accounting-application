<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class DollarExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'category',
        'amount',
        'exchange_rate',
        'bdt_amount',
        'description',
        'user_id',
        'category_id',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'bdt_amount' => 'decimal:2',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($dollarExpense) {
            // Auto-calculate BDT amount if not provided
            if ($dollarExpense->amount && $dollarExpense->exchange_rate && !$dollarExpense->bdt_amount) {
                $dollarExpense->bdt_amount = $dollarExpense->amount * $dollarExpense->exchange_rate;
            }
        });
    }

    /**
     * Get the user that owns the dollar expense.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that owns the dollar expense.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope a query to only include dollar expenses from a specific date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope a query to only include dollar expenses from current month.
     */
    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('date', Carbon::now()->month)
                    ->whereYear('date', Carbon::now()->year);
    }

    /**
     * Scope a query to only include dollar expenses from current year.
     */
    public function scopeCurrentYear($query)
    {
        return $query->whereYear('date', Carbon::now()->year);
    }

    /**
     * Get the BDT equivalent amount.
     */
    public function getBdtAmountAttribute($value)
    {
        return $value ?? ($this->amount * $this->exchange_rate);
    }
}