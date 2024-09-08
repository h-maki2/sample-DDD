<?php

namespace organizationManagement\test\common\helper\employee;

use organizationManagement\domain\model\employee\Employee;
use organizationManagement\domain\model\employee\EmployeeId;
use organizationManagement\domain\model\employee\EmployeeName;
use organizationManagement\sqlInfrastructure\persistence\EloquentEmployeeRepository;

class EmployeeTestDataCreator
{
    private EloquentEmployeeRepository $employeeRepository;

    public function __construct(EloquentEmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function create(
        EmployeeId $id,
        EmployeeName $name = new EmployeeName('test user'),
        bool $isRetired = false
    ): Employee
    {
        $testEmployeeFactory = new TestEmployeeFactory();
        $employee = $testEmployeeFactory->create(
            $id,
            $name,
            $isRetired
        );

        $this->employeeRepository->save($employee);

        return $employee;
    }
}