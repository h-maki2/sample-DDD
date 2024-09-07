<?php

use PHPUnit\Framework\TestCase;
use organizationManagement\domain\model\employee\Employee;
use organizationManagement\domain\model\employee\EmployeeName;
use organizationManagement\domain\model\employee\EmployeeId;
use organizationManagement\domain\model\common\exception\IllegalStateException;

class EmployeeTest extends TestCase
{
    public function test_新しくEmployeeを生成すると、退職済みのフラグがfalseのEmployeeが生成される()
    {
        // given

        // when
        $employeeId = new EmployeeId('1');
        $employeeName = new EmployeeName('test user');
        $employee = Employee::create($employeeId, $employeeName);

        // then
        $this->assertEquals(false, $employee->isRetired());
        
        // 以下の属性は引数の値がそのまま設定される
        $this->assertEquals($employeeId->value(), $employee->id()->value());
        $this->assertEquals($employeeName->value(), $employee->name()->value());
    }

    public function test_従業員がまだ退職していない場合に、正しく名前を変更できることを確認()
    {
        // given
        $isRetired = false; // まだ退職していない場合
        $employee = Employee::reconstruct(
            new EmployeeId('1'), 
            new EmployeeName('test user'),
            $isRetired
        );

        // when
        $changedEmployeeName = new EmployeeName('changed name');
        $employee->changename($changedEmployeeName);

        // then
        $this->assertEquals($changedEmployeeName->value(), $employee->name()->value());
    }

    public function test_従業員が退職済みの場合に、名前を変更した際に例外が発生することを確認()
    {
        // given: 退職済みのEmployeeインスタンスを生成
        $isRetired = true; 
        $employee = Employee::reconstruct(
            new EmployeeId('1'), 
            new EmployeeName('test user'),
            $isRetired
        );

        // when

        // then
        $this->expectException(IllegalStateException::class);
        $this->expectExceptionMessage('従業員の名前を変更できません。');
        $changedEmployeeName = new EmployeeName('changed name');
        $employee->changename($changedEmployeeName);
    }
}