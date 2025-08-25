<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Financial Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .summary {
            margin-bottom: 30px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .summary-card {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
        }
        .summary-card h3 {
            margin: 0 0 10px 0;
            color: #333;
        }
        .summary-card .amount {
            font-size: 18px;
            font-weight: bold;
        }
        .income { color: #10b981; }
        .expense { color: #ef4444; }
        .profit { color: #3b82f6; }
        .section {
            margin-bottom: 30px;
        }
        .section h2 {
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Financial Report</h1>
        <p>Period: {{ $dateFrom }} to {{ $dateTo }}</p>
        <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <div class="summary">
        <h2>Summary</h2>
        <div class="summary-grid">
            <div class="summary-card">
                <h3>Total Income</h3>
                <div class="amount income">৳{{ number_format($totalIncome, 2) }}</div>
            </div>
            <div class="summary-card">
                <h3>Total Expenses</h3>
                <div class="amount expense">৳{{ number_format($totalExpenses, 2) }}</div>
            </div>
            <div class="summary-card">
                <h3>Net Profit/Loss</h3>
                <div class="amount profit">৳{{ number_format($netProfit, 2) }}</div>
            </div>
        </div>
    </div>

    @if($incomeByCategory->count() > 0)
    <div class="section">
        <h2>Income by Category</h2>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Count</th>
                    <th class="text-right">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($incomeByCategory as $category => $data)
                <tr>
                    <td>{{ $category }}</td>
                    <td>{{ $data['count'] }}</td>
                    <td class="text-right">৳{{ number_format($data['total'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($expenseByCategory->count() > 0)
    <div class="section">
        <h2>Expenses by Category</h2>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Count</th>
                    <th class="text-right">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenseByCategory as $category => $data)
                <tr>
                    <td>{{ $category }}</td>
                    <td>{{ $data['count'] }}</td>
                    <td class="text-right">৳{{ number_format($data['total'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($incomes->count() > 0)
    <div class="section">
        <h2>Income Details</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Source</th>
                    <th>Category</th>
                    <th class="text-right">Amount</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach($incomes as $income)
                <tr>
                    <td>{{ $income->date->format('Y-m-d') }}</td>
                    <td>{{ $income->source }}</td>
                    <td>{{ $income->category->name ?? 'N/A' }}</td>
                    <td class="text-right">৳{{ number_format($income->amount, 2) }}</td>
                    <td>{{ $income->description }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($expenses->count() > 0)
    <div class="section">
        <h2>Expense Details</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th class="text-right">Amount</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $expense)
                <tr>
                    <td>{{ $expense->date->format('Y-m-d') }}</td>
                    <td>{{ $expense->category->name ?? 'N/A' }}</td>
                    <td class="text-right">৳{{ number_format($expense->amount, 2) }}</td>
                    <td>{{ $expense->description }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>This report was generated automatically by the Accounting System</p>
    </div>
</body>
</html>