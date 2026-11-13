<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{ValidatorService, TransactionService};

class TransactionController
{
    public function __construct(
        private TemplateEngine $view,
        private ValidatorService $validatorService,
        private TransactionService $transactionService
    ) {
        $this->transactionService = $transactionService;
    }

    public function createViewIncome()
    {
        echo $this->view->render("transactions/income.php");
    }

    public function createIncome()
    {
        $this->validatorService->validateIncome($_POST);

        $this->transactionService->createIncome($_POST);

        redirectTo('/main-menu');
    }

    public function createViewExpense()
    {
        echo $this->view->render("transactions/expense.php");
    }

    public function createExpense()
    {
        $this->validatorService->validateExpense($_POST);

        $this->transactionService->createExpense($_POST);

        redirectTo('/main-menu');
    }

    public function createShowBalance()
    {
        echo $this->view->render("transactions/balance.php");
    }

    public function createViewShowBalance()
    {
        $userId = $_SESSION['user'] ?? null;

        if (!$userId) {
            header('Location: /login');
            exit;
        }

        $selectedPeriod = $_GET['chooseDate'] ?? $_SESSION['selected_period'] ?? 'current_month';
        $_SESSION['selected_period'] = $selectedPeriod;

        $customStart = $_GET['customStartDate'] ?? null;
        $customEnd = $_GET['customEndDate'] ?? null;

        $dates = $this->transactionService->calculateDates($selectedPeriod, $customStart, $customEnd);
        $incomes = $this->transactionService->getUserIncomes((int) $userId, $dates['start'], $dates['end']);
        $expenses = $this->transactionService->getUserExpenses((int) $userId, $dates['start'], $dates['end']);

        $balance = $this->transactionService->createBalance(
            $incomes['sumIncomes'],
            $expenses['sumExpenses']
        );

        $viewData = [
            'selected_period' => $selectedPeriod,
            'startDate' => $dates['start'],
            'endDate' => $dates['end'],
            'incomes' => $incomes['incomes'],
            'incomesCount' => $incomes['incomesCount'],
            'sumIncomes' => $incomes['sumIncomes'],
            'expenses' => $expenses['expenses'],
            'expensesCount' => $expenses['expensesCount'],
            'sumExpenses' => $expenses['sumExpenses'],
            'balance' => $balance,
            'incomeByCategory' => $incomes['incomeByCategory'],
            'expenseByCategory' => $expenses['expenseByCategory']
        ];

        echo $this->view->render("transactions/balance.php", $viewData);
    }
}
