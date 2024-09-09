<?php

namespace organizationManagement\application\common;

class EmployeeData
{
    private string $id;
    private string $name;
    private bool $retired;

    public function __construct(string $id, string $name, bool $retired)
    {
        $this->id = $id;
        $this->name = $name;
        $this->retired = $retired;
    }

    public function id(): string
    {
        return $this->id;
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