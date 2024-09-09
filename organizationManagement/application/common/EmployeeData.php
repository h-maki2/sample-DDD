<?php

namespace organizationManagement\application\common;

class EmployeeData implements JsonSerializable
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

    public function jsonSerialize(): array
    {
        return [
            'employeeName' => $this->name,
            'retired' => $this->retired
        ];
    }
}