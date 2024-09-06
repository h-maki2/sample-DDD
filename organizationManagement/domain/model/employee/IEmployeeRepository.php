<?php

namespace organizationManagement\domain\model\employee;

interface IEmployeeRepository
{
    public function findById(EmployeeId $id): Employee;
    
    public function save(Employee $employee): void;

    public function nextIdentity(): EmployeeId;
}