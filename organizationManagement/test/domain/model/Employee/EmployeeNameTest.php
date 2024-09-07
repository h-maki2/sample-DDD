<?php

use PHPUnit\Framework\TestCase;
use organizationManagement\domain\model\employee\EmployeeName;

class EmployeeNameTest extends TestCase
{
    public function test_名前が空文字列の時に例外が発生することを確認()
    {
        $this->expectException(InvalidArgumentException::class);
        new EmployeeName('');
    }

    public function test_正常にインスタンスが生成されることを確認()
    {
        $employeeName = new EmployeeName('test user');
        $this->assertInstanceOf(EmployeeName::class, $employeeName);
    }
}