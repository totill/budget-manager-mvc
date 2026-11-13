<?php

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class NumericRule implements RuleInterface
{
    public function validate(array $data, string $field, array $params): bool
    {
        $price = str_replace(',', '.', $data[$field]);

        return is_numeric($price);
    }

    public function getMessage(array $data, string $field, array $params): string
    {
        return "Only numbers allowed";
    }
}
