<?php

namespace organizationManagement\domain\model\employee;

class EmployeeName
{
    private string $value;

    public function __construct(string $name)
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('名前が不正です。');
        }

        $this->value = $name;
    }

    public function value(): string
    {
        return $this->value;
    }
}