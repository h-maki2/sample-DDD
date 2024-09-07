<?php

namespace organizationManagement\domain\model\employee;

use organizationManagement\domain\model\common\exception\IllegalStateException;

class Employee
{
    private EmployeeId $id;
    private EmployeeName $name;
    private bool $isRetired;

    private function __construct(
        EmployeeId $id,
        EmployeeName $name,
        bool $isRetired
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->isRetired = $isRetired;
    }

    public static function create(EmployeeId $id, EmployeeName $name): self
    {
        return new self(
            $id,
            $name,
            false
        );
    }

    public static function reconstruct(
        EmployeeId $id,
        EmployeeName $name,
        bool $isRetired
    ): self
    {
        return new self($id, $name, $isRetired);
    }

    public function id(): EmployeeId
    {
        return $this->id;
    }

    public function name(): EmployeeName
    {
        return $this->name;
    }

    public function isRetired(): bool
    {
        return $this->isRetired;
    }
    
    /**
     * 名前を変更する
     * 既に退職済みの場合は名前を変更できない
     */
    public function changeName(EmployeeName $name): void
    {
        if ($this->isRetired) {
            throw new IllegalStateException('従業員の名前を変更できません。');
        }

        $this->name = $name;
    }
}
