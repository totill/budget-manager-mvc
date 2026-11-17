<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use App\Exceptions\ValidationException;

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
                'amount' => $this->normalizeAmount((string) $formData['amount']),
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
                'amount' => $this->normalizeAmount((string) $formData['amount']),
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

        $incomeByCategory = $this->sumByCategory('income', $userId, $startDate, $endDate);

        return [
            'incomes' => $incomes,
            'incomesCount' => count($incomes),
            'sumIncomes' => array_sum($incomeByCategory),
            'incomeByCategory' => $incomeByCategory
        ];
    }

    public function getUserExpenses(int $userId,  string $startDate, string $endDate): array
    {
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

        $expenseByCategory = $this->sumByCategory('expense', $userId, $startDate, $endDate);

        return [
            'expenses' => $expenses,
            'expensesCount' => count($expenses),
            'sumExpenses' => array_sum($expenseByCategory),
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

    private function normalizeAmount(string $amount): string
    {
        $amount = str_replace([' ', ','], ['', '.'], trim($amount));

        if (!is_numeric($amount) || (float) $amount <= 0) {
            throw new ValidationException(['amount' => ['Kwota musi być liczbą większą od 0']]);
        }

        return number_format((float) $amount, 2, '.', '');
    }

    private function sumByCategory(string $table, int $userId, string $start, string $end): array
    {
        $allowed = ['income', 'expense'];
        if (!in_array($table, $allowed, true)) {
            throw new \InvalidArgumentException('Invalid table');
        }

        $rows = $this->db->query(
            "SELECT category, SUM(amount) AS total
         FROM {$table}
         WHERE user_id = :user_id AND date BETWEEN :start AND :end
         GROUP BY category
         ORDER BY total DESC",
            ['user_id' => $userId, 'start' => "$start 00:00:00", 'end' => "$end 23:59:59"]
        )->findAll();

        return array_column($rows, 'total', 'category');
    }
}
