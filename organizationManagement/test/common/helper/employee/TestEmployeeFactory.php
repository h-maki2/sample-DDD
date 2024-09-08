<?php

namespace organizationManagement\test\common\helper\employee;

use organizationManagement\domain\model\employee\Employee;
use organizationManagement\domain\model\employee\EmployeeId;
use organizationManagement\domain\model\employee\EmployeeName;

class TestEmployeeFactory
{
    public function create(
        EmployeeId $id,
        EmployeeName $name = new EmployeeName('test user'),
        bool $isRetired = false
    ): Employee
    {
        return Employee::reconstruct(
            $id,
            $name,
            $isRetired
        );
    }
}