<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Income;

class BalanceController extends Controller
{
    /**
     * Get available dollar balance for authenticated user
     */
    public function getDollarBalance()
    {
        $balance = Income::getAvailableDollarBalance(auth()->id());
        
        return response()->json([
            'balance' => $balance,
            'formatted_balance' => number_format($balance, 2)
        ]);
    }
}
