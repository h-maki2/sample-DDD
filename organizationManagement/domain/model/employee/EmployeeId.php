<?php

namespace organizationManagement\domain\model\employee;

use InvalidArgumentException;

class EmployeeId
{
    private string $value;

    public function __construct(string $value)
    {
        if (empty($value)) {
            throw new InvalidArgumentException('EmployeeIdが空です。');
        }
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
