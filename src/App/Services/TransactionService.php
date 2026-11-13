<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;

class TransactionService
{
    public function __construct(private Database $db) {}

    public function createIncome(array $formData)
    {
        $formattedDate = "{$formData['date']} 00:00:00";

        $this->db->query(
            "INSERT INTO income(user_id, amount, date, category, description)
            VALUES(:user_id, :amount, :date, :category, :description)",
            [
                'user_id' => $_SESSION['user'],
                'amount' => $formData['amount'],
                'date' => $formattedDate,
                'category' => $formData['category'],
                'description' => $formData['description']
            ]
        );
    }

    public function createExpense(array $formData)
    {
        $formattedDate = "{$formData['date']} 00:00:00";

        $this->db->query(
            "INSERT INTO expense(user_id, amount, date, category, payment_method, description)
            VALUES(:user_id, :amount, :date, :category, :payment_method, :description)",
            [
                'user_id' => $_SESSION['user'],
                'amount' => $formData['amount'],
                'date' => $formattedDate,
                'category' => $formData['category'],
                'payment_method' => $formData['payment_method'],
                'description' => $formData['description']
            ]
        );
    }

    public function calculateDates(string $period, ?string $customStart = null, ?string $customEnd = null): array
    {
        $currentDay = date('Y-m-d');
        $startDate = $currentDay;
        $endDate = $currentDay;

        switch ($period) {
            case 'current_month':
                $startDate = date('Y-m-01');
                $endDate = date('Y-m-t');
                break;

            case 'last_month':
                $lastMonthTimestamp = strtotime('first day of last month');
                $startDate = date('Y-m-01', $lastMonthTimestamp);
                $endDate = date('Y-m-t', $lastMonthTimestamp);
                break;

            case 'current_year':
                $startDate = date('Y-01-01');
                $endDate = date('Y-12-31');
                break;

            case 'custom':
                $startDate = !empty($customStart) ? $customStart : $currentDay;
                $endDate = !empty($customEnd) ? $customEnd : $currentDay;

                if ($startDate > $endDate) {
                    [$startDate, $endDate] = [$endDate, $startDate];
                }
                break;
        }

        return [
            'start' => $startDate,
            'end' => $endDate
        ];
    }

    public function getUserIncomes(int $userId,  string $startDate, string $endDate): array
    {
        // $searchTerm = addcslashes($_GET['s'] ?? '', '%_');
        $params =             [
            'user_id' => $userId,
            'start_date' => $startDate . ' 00:00:00',
            'end_date' => $endDate . ' 23:59:59'
        ];

        $incomes = $this->db->query(
            "SELECT id, amount, DATE_FORMAT(date, '%Y-%m-%d') as date, category, description 
            FROM income 
            WHERE user_id = :user_id 
            AND date BETWEEN :start_date AND :end_date 
            ORDER BY date DESC",
            $params
        )->findAll();

        $sumIncomes = 0.0;
        $incomeByCategory = [];

        foreach ($incomes as $income) {
            $amount = (float) $income['amount'];
            $category = $income['category'];

            $sumIncomes += $amount;
            $incomeByCategory[$category] = ($incomeByCategory[$category] ?? 0.0) + $amount;
        }

        arsort($incomeByCategory);

        return [
            'incomes' => $incomes,
            'incomesCount' => count($incomes),
            'sumIncomes' => $sumIncomes,
            'incomeByCategory' => $incomeByCategory
        ];
    }

    public function getUserExpenses(int $userId,  string $startDate, string $endDate): array
    {
        // $searchTerm = addcslashes($_GET['s'] ?? '', '%_');
        $params =             [
            'user_id' => $userId,
            'start_date' => $startDate . ' 00:00:00',
            'end_date' => $endDate . ' 23:59:59'
        ];

        $expenses = $this->db->query(
            "SELECT id, amount, DATE_FORMAT(date, '%Y-%m-%d') as date, payment_method, category, description 
            FROM expense 
            WHERE user_id = :user_id 
            AND date BETWEEN :start_date AND :end_date 
            ORDER BY date DESC",
            $params
        )->findAll();

        $sumExpenses = 0.0;
        $expenseByCategory = [];

        foreach ($expenses as $expense) {
            $amount = (float) $expense['amount'];
            $category = $expense['category'];

            $sumExpenses += $amount;
            $expenseByCategory[$category] = ($expenseByCategory[$category] ?? 0.0) + $amount;
        }

        arsort($expenseByCategory);

        return [
            'expenses' => $expenses,
            'expensesCount' => count($expenses),
            'sumExpenses' => $sumExpenses,
            'expenseByCategory' => $expenseByCategory
        ];
    }

    public function createBalance(float $sumIncomes, float $sumExpenses): array
    {
        $balance = $sumIncomes - $sumExpenses;

        if ($balance > 0) {
            return [
                'amount' => number_format($balance, 2, ',', ' '),
                'balanceClass' => 'balance-positive',
                'alertClass' => 'alert-success',
                'alertText' => 'Świetnie! Bardzo dobrze zarządzasz finansami.'
            ];
        }

        if ($balance < 0) {
            return [
                'amount' => number_format($balance, 2, ',', ' '),
                'balanceClass' => 'balance-negative',
                'alertClass' => 'alert-danger',
                'alertText' => 'Uwaga! Wpadasz w długi.'
            ];
        }

        return [
            'amount' => number_format($balance, 2, ',', ' '),
            'balanceClass' => 'balance-zero',
            'alertClass' => 'alert-warning',
            'alertText' => 'Bilans zerowy.'
        ];
    }
}
