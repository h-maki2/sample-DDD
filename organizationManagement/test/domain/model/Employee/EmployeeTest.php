<?php

use PHPUnit\Framework\TestCase;
use organizationManagement\domain\model\employee\Employee;
use organizationManagement\domain\model\employee\EmployeeName;
use organizationManagement\domain\model\employee\EmployeeId;

class EmployeeTest extends TestCase
{
    public function test_新しくEmployeeを生成すると、退職済みのフラグがfalseのEmployeeが生成される()
    {
        // given

        // when
        $employeeId = new EmployeeId('1');
        $employeeName = new EmployeeName('test user');
    }
}