<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\Categories;
use Framework\Exceptions\ValidationException;
use Framework\Validator;
use Framework\Rules\{
    RequiredRule,
    EmailRule,
    MinRule,
    InRule,
    UrlRule,
    MatchRule,
    LengthMaxRule,
    NumericRule,
    DateFormatRule
};

class ValidatorService
{
    private Validator $validator;

    public function __construct()
    {
        $this->validator = new Validator();

        $this->validator->add('name', new RequiredRule());
        $this->validator->add('required', new RequiredRule());
        $this->validator->add('email', new EmailRule());
        // $this->validator->add('min', new MinRule());
        // $this->validator->add('in', new InRule());
        // $this->validator->add('url', new UrlRule());
        $this->validator->add('match', new MatchRule());
        $this->validator->add('lengthMax', new LengthMaxRule());
        $this->validator->add('numeric', new NumericRule());
        $this->validator->add('dateFormat', new DateFormatRule());
        // $this->validator->add('amount', new AmountRule());
    }

    public function validateRegister(array $formData)
    {
        $this->validator->validate($formData, [
            'name' => ['required', 'name'],
            'email' => ['required', 'email'],
            'password' => ['required'],
            'confirmPassword' => ['required', 'match:password'],
            'tos' => ['required']
        ]);
    }

    public function validateLogin(array $formData)
    {
        $this->validator->validate($formData, [
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
    }

    public function validateTransaction(array $formData)
    {
        $this->validator->validate($formData, [
            'description' => ['required', 'lengthMax:255'],
            'amount' => ['required', 'numeric'],
            'date' => ['required', 'dateFormat:Y-m-d']
        ]);
    }

    public function validateIncome(array $formData)
    {
        $this->validator->validate($formData, [
            'amount' => ['required', 'numeric'],
            'date' => ['required', 'dateFormat:Y-m-d'],
            'category' => ['required'],
            'description' => ['lengthMax:255']
        ]);

        if (!in_array($formData['category'], Categories::INCOME, true)) {
            throw new ValidationException([
                'category' => ['Nieprawidłowa kategoria przychodu']
            ]);
        }
    }

    public function validateExpense(array $formData)
    {
        $this->validator->validate($formData, [
            'amount' => ['required', 'numeric'],
            'date' => ['required', 'dateFormat:Y-m-d'],
            'category' => ['required'],
            'payment_method' => ['required'],
            'description' => ['lengthMax:255']
        ]);

        if (!in_array($formData['category'], Categories::EXPENSE, true)) {
            throw new ValidationException(['category' => ['Nieprawidłowa kategoria']]);
        }
        if (!in_array($formData['payment_method'], Categories::PAYMENT, true)) {
            throw new ValidationException(['payment_method' => ['Nieprawidłowa metoda płatności']]);
        }
    }
}
