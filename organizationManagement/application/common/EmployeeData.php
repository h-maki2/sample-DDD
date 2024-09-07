<?php

namespace organizationManagement\application\common\EmployeeData;

class EmployeeData
{
    private string $name;
    private bool $retired;

    public function __construct(string $name, bool $retired)
    {
        $this->name = $name;
        $this->retired = $retired;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function isRetired(): bool
    {
        return $this->retired;
    }
}